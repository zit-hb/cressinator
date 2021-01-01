<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201230214856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE recording_sources (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5D0B5E90FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recording_sources ADD CONSTRAINT FK_5D0B5E90FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE recordings DROP FOREIGN KEY FK_E9D79C6EFE54D947');
        $this->addSql('DROP INDEX IDX_E9D79C6EFE54D947 ON recordings');
        $this->addSql('ALTER TABLE recordings CHANGE group_id source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recordings ADD CONSTRAINT FK_E9D79C6E953C1C61 FOREIGN KEY (source_id) REFERENCES recording_sources (id)');
        $this->addSql('CREATE INDEX IDX_E9D79C6E953C1C61 ON recordings (source_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE recordings DROP FOREIGN KEY FK_E9D79C6E953C1C61');
        $this->addSql('DROP TABLE recording_sources');
        $this->addSql('DROP INDEX IDX_E9D79C6E953C1C61 ON recordings');
        $this->addSql('ALTER TABLE recordings CHANGE source_id group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recordings ADD CONSTRAINT FK_E9D79C6EFE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('CREATE INDEX IDX_E9D79C6EFE54D947 ON recordings (group_id)');
    }
}
