FROM php:8.4.16-fpm-alpine AS base

ARG EXTENSION_INSTALLER_VERSION=2.9.27
ARG EXTENSION_INSTALLER_CHECKSUM=03c746015d61f26f4e5aa8f0bd9ae14a567e298249950e81a0d608191f8d323b

ARG PHPFPM_HEALTHCHECK=v0.6.0
ARG PHPFPM_HEALTHCHECK_CHECKSUM=53bc616c4a30f029b98bff48fdeb0c4da252cb11e4f86656a8222a67dc4e5009

ADD --chmod=0755 \
    --checksum=sha256:$EXTENSION_INSTALLER_CHECKSUM \
    https://github.com/mlocati/docker-php-extension-installer/releases/download/$EXTENSION_INSTALLER_VERSION/install-php-extensions \
    /usr/local/bin/

ADD --chmod=0755 \
    --checksum=sha256:$PHPFPM_HEALTHCHECK_CHECKSUM \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/$PHPFPM_HEALTHCHECK/php-fpm-healthcheck \
    /usr/local/bin/

ENV LC_ALL=uk_UA.UTF-8

RUN export MAKEFLAGS="-j$(nproc)" \
    && apk add --no-cache --virtual .build-deps icu-dev \
    && apk add --no-cache icu-data-full icu musl-locales musl-locales-lang fcgi \
    && install-php-extensions \
        intl \
        opcache \
        pdo \
        pdo_mysql \
        bcmath \
        zip-1.22.4 \
        pcntl \
        xsl \
        apcu-5.1.24 \
        amqp-2.1.2 \
        redis-6.2.0 \
    && apk del .build-deps

RUN php -v \
    && php -m

RUN apk add tzdata \
    && cp /usr/share/zoneinfo/Europe/Kyiv /etc/localtime \
    && echo "Europe/Kyiv" > /etc/timezone \
    && apk del tzdata

RUN adduser -s /bin/sh -D -u 1000 app \
    && addgroup app www-data

WORKDIR /app

################################################################################################################################

FROM base AS dev

ENV APP_ENV=dev
ENV APP_DEBUG=1

RUN apk add --no-cache bash sudo \
    && install-php-extensions \
        xdebug-3.4.3 \
        spx-v0.4.18

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

RUN addgroup --system sudo && addgroup app sudo \
    && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

COPY --from=node:24.14.1-alpine /usr/local/bin/node /usr/local/bin/
COPY --from=node:24.14.1-alpine /usr/local/include /usr/local/include/
COPY --from=node:24.14.1-alpine /usr/local/lib /usr/local/lib/

RUN cd /usr/local/bin \
    && ln -s ../lib/node_modules/npm/bin/npm-cli.js npm \
    && ln -s ../lib/node_modules/npm/bin/npx-cli.js npx

USER app

CMD ["php-fpm"]

################################################################################################################################

FROM base AS prod

ENV APP_ENV=prod
ENV APP_DEBUG=0

COPY . /app

RUN chown -R app: /app \
    && rm -rf /usr/local/include \
              /usr/share/php \
              /usr/share/X11 \
              /usr/src/php* \
              /var/cache/apk/* \
    && find /usr/local/lib/php/ ! -name "*.so" -delete

USER app

################################################################################################################################

FROM base AS test

ENV APP_ENV=test
ENV APP_DEBUG=0

RUN apk add --no-cache bash sudo \
    && install-php-extensions xdebug-3.4.3 \
    && rm -rf /usr/local/include \
              /usr/share/php \
              /usr/share/X11 \
              /usr/src/php* \
              /var/cache/apk/* \
    && find /usr/local/lib/php/ ! -name "*.so" -delete


RUN adduser -s /bin/sh -H -D runner \
    && addgroup runner root \
    && addgroup runner www-data

USER runner

CMD ["php-fpm", "-F", "-R"]
