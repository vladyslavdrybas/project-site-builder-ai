<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820162249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_variant (id UUID NOT NULL, project_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, meta JSON DEFAULT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3D39EBC166D1F9C ON fimi_variant (project_id)');
        $this->addSql('COMMENT ON COLUMN fimi_variant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_variant.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_variant ADD CONSTRAINT FK_A3D39EBC166D1F9C FOREIGN KEY (project_id) REFERENCES fimi_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant DROP CONSTRAINT FK_A3D39EBC166D1F9C');
        $this->addSql('DROP TABLE fimi_variant');
    }
}
