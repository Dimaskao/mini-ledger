# MiniLedger

## Traefik Proxy

### Setup

1. Update `/etc/hosts`
2. Create ssl certificates `mkdir -p .ssl && (cd .ssl && mkcert -cert-file tls.crt -key-file tls.key "*.dev.loc")`
3. Start `docker compose up -d`

### Usage

Dashboard URL - `https://proxy.dev.loc/dashboard/#/`
