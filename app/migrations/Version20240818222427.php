<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240818222427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_project (id UUID NOT NULL, owner_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D23F1FF7E3C61F9 ON fimi_project (owner_id)');
        $this->addSql('COMMENT ON COLUMN fimi_project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_project.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_project ADD CONSTRAINT FK_7D23F1FF7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fimi_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_user DROP is_verified');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project DROP CONSTRAINT FK_7D23F1FF7E3C61F9');
        $this->addSql('DROP TABLE fimi_project');
        $this->addSql('ALTER TABLE fimi_user ADD is_verified BOOLEAN NOT NULL');
    }
}
