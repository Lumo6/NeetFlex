<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219135730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE artist ADD COLUMN name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE artist ADD COLUMN "desc" VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE artist ADD COLUMN image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN artist VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__artist AS SELECT id FROM artist');
        $this->addSql('DROP TABLE artist');
        $this->addSql('CREATE TABLE artist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO artist (id) SELECT id FROM __temp__artist');
        $this->addSql('DROP TABLE __temp__artist');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO event (id) SELECT id FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }
}
