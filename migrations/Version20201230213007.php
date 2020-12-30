<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201230213007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measurement_sources (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, unit VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9C5D219FFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measurements (id INT AUTO_INCREMENT NOT NULL, source_id INT DEFAULT NULL, value VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_71920F21953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recordings (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E9D79C6EFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E97BA2F5EB (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE measurement_sources ADD CONSTRAINT FK_9C5D219FFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE measurements ADD CONSTRAINT FK_71920F21953C1C61 FOREIGN KEY (source_id) REFERENCES measurement_sources (id)');
        $this->addSql('ALTER TABLE recordings ADD CONSTRAINT FK_E9D79C6EFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE measurement_sources DROP FOREIGN KEY FK_9C5D219FFE54D947');
        $this->addSql('ALTER TABLE recordings DROP FOREIGN KEY FK_E9D79C6EFE54D947');
        $this->addSql('ALTER TABLE measurements DROP FOREIGN KEY FK_71920F21953C1C61');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE measurement_sources');
        $this->addSql('DROP TABLE measurements');
        $this->addSql('DROP TABLE recordings');
        $this->addSql('DROP TABLE users');
    }
}
