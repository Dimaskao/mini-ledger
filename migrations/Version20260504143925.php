<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504143925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE activity_log (
            id INT AUTO_INCREMENT NOT NULL,
            entity_type VARCHAR(255) NOT NULL,
            entity_id INT NOT NULL,
            action VARCHAR(255) NOT NULL,
            old_values_json JSON DEFAULT NULL,
            new_values_json JSON DEFAULT NULL,
            created_at DATETIME NOT NULL,

            PRIMARY KEY (id))
        ');

        $this->addSql('CREATE TABLE client (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            company_name VARCHAR(255) DEFAULT NULL,
            status VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,

            PRIMARY KEY (id))
        ');

        $this->addSql('CREATE TABLE invoice (
            id INT AUTO_INCREMENT NOT NULL,
            number VARCHAR(255) DEFAULT NULL,
            issue_date DATETIME DEFAULT NULL,
            due_date DATETIME DEFAULT NULL,
            currency VARCHAR(255) DEFAULT NULL,
            total_amount_minor BIGINT DEFAULT NULL,
            paid_amount_minor BIGINT DEFAULT NULL,
            status VARCHAR(255) NOT NULL,
            notes LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            client_id INT NOT NULL,

            INDEX IDX_9065174419EB6921 (client_id),
            PRIMARY KEY (id))
        ');

        $this->addSql('CREATE TABLE notification (
            id INT AUTO_INCREMENT NOT NULL,
            type VARCHAR(255) NOT NULL,
            channel VARCHAR(255) NOT NULL,
            status VARCHAR(255) NOT NULL,
            payload_json JSON DEFAULT NULL,
            error_message LONGTEXT DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            client_id INT NOT NULL,
            invoice_id INT DEFAULT NULL,

            INDEX IDX_BF5476CA19EB6921 (client_id),
            INDEX IDX_BF5476CA2989F1FD (invoice_id),
            PRIMARY KEY (id))
        ');

        $this->addSql('CREATE TABLE payment (
            id INT AUTO_INCREMENT NOT NULL,
            amount_minor BIGINT NOT NULL,
            paid_at DATETIME NOT NULL,
            method VARCHAR(255) NOT NULL,
            reference VARCHAR(255) DEFAULT NULL,
            comment VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            invoice_id INT NOT NULL,

            INDEX IDX_6D28840D2989F1FD (invoice_id),
            PRIMARY KEY (id))
        ');

        $this->addSql('CREATE TABLE messenger_messages (
            id BIGINT AUTO_INCREMENT NOT NULL,
            body LONGTEXT NOT NULL,
            headers LONGTEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL,
            available_at DATETIME NOT NULL,
            delivered_at DATETIME DEFAULT NULL,

            INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id),
            PRIMARY KEY (id))
        ');

        $this->addSql('ALTER TABLE invoice
            ADD CONSTRAINT FK_9065174419EB6921
            FOREIGN KEY (client_id)
            REFERENCES client (id)
        ');

        $this->addSql('ALTER TABLE notification
            ADD CONSTRAINT FK_BF5476CA19EB6921
            FOREIGN KEY (client_id)
            REFERENCES client (id)
        ');

        $this->addSql('ALTER TABLE notification
            ADD CONSTRAINT FK_BF5476CA2989F1FD
            FOREIGN KEY (invoice_id)
            REFERENCES invoice (id)
        ');

        $this->addSql('ALTER TABLE payment
            ADD CONSTRAINT FK_6D28840D2989F1FD
            FOREIGN KEY (invoice_id)
            REFERENCES invoice (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174419EB6921');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA19EB6921');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA2989F1FD');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D2989F1FD');
        $this->addSql('DROP TABLE activity_log');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
