<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190427150941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recordings (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recording_entity_source_entity (recording_entity_id INT NOT NULL, source_entity_id INT NOT NULL, INDEX IDX_156D757667E5120A (recording_entity_id), INDEX IDX_156D7576E9BBEE93 (source_entity_id), PRIMARY KEY(recording_entity_id, source_entity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recording_entity_source_entity ADD CONSTRAINT FK_156D757667E5120A FOREIGN KEY (recording_entity_id) REFERENCES recordings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recording_entity_source_entity ADD CONSTRAINT FK_156D7576E9BBEE93 FOREIGN KEY (source_entity_id) REFERENCES sources (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recording_entity_source_entity DROP FOREIGN KEY FK_156D757667E5120A');
        $this->addSql('DROP TABLE recordings');
        $this->addSql('DROP TABLE recording_entity_source_entity');
    }
}
