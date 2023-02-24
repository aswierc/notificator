<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\NotificationPublisher\Domain\Channel;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214070836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_channel_flag (channel_name VARCHAR(100) NOT NULL, is_enabled BOOLEAN NOT NULL, PRIMARY KEY(channel_name))');
        $this->addSql('CREATE TABLE app_message (id VARCHAR(100) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_message_attempt (id VARCHAR(100) NOT NULL, message_id VARCHAR(100) DEFAULT NULL, status VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, is_error BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2BE1DE3537A1329 ON app_message_attempt (message_id)');
        $this->addSql('CREATE TABLE app_message_identifier (id VARCHAR(100) NOT NULL, message_id VARCHAR(100) DEFAULT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8EF648D537A1329 ON app_message_identifier (message_id)');
        $this->addSql('ALTER TABLE app_message_attempt ADD CONSTRAINT FK_D2BE1DE3537A1329 FOREIGN KEY (message_id) REFERENCES app_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_message_identifier ADD CONSTRAINT FK_D8EF648D537A1329 FOREIGN KEY (message_id) REFERENCES app_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        foreach (Channel::getOptions() as $channelName) {
            $this->addSql(sprintf("INSERT INTO app_channel_flag (channel_name, is_enabled) VALUES ('%s', true)", $channelName));
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_message_attempt DROP CONSTRAINT FK_D2BE1DE3537A1329');
        $this->addSql('ALTER TABLE app_message_identifier DROP CONSTRAINT FK_D8EF648D537A1329');
        $this->addSql('DROP TABLE app_channel_flag');
        $this->addSql('DROP TABLE app_message');
        $this->addSql('DROP TABLE app_message_attempt');
        $this->addSql('DROP TABLE app_message_identifier');
    }
}
