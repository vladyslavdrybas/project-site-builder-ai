<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240824202218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_media (id VARCHAR(128) NOT NULL, owner_id UUID DEFAULT NULL, mimetype VARCHAR(255) NOT NULL, extension VARCHAR(20) NOT NULL, size INT NOT NULL, content BYTEA NOT NULL, version INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBA57BD17E3C61F9 ON fimi_media (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX owner_id_idx ON fimi_media (owner_id, id)');
        $this->addSql('COMMENT ON COLUMN fimi_media.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE fimi_media_tag (media_id VARCHAR(128) NOT NULL, tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(media_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_5768A4A0EA9FDD75 ON fimi_media_tag (media_id)');
        $this->addSql('CREATE INDEX IDX_5768A4A0BAD26311 ON fimi_media_tag (tag_id)');
        $this->addSql('CREATE TABLE fimi_tag (id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fimi_variant (id UUID NOT NULL, project_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, meta JSON DEFAULT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_active BOOLEAN DEFAULT false NOT NULL, weight INT DEFAULT 50 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3D39EBC166D1F9C ON fimi_variant (project_id)');
        $this->addSql('COMMENT ON COLUMN fimi_variant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_variant.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE fimi_variant_media (variant_id UUID NOT NULL, media_id VARCHAR(128) NOT NULL, PRIMARY KEY(variant_id, media_id))');
        $this->addSql('CREATE INDEX IDX_CF5F2A13B69A9AF ON fimi_variant_media (variant_id)');
        $this->addSql('CREATE INDEX IDX_CF5F2A1EA9FDD75 ON fimi_variant_media (media_id)');
        $this->addSql('COMMENT ON COLUMN fimi_variant_media.variant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_media ADD CONSTRAINT FK_CBA57BD17E3C61F9 FOREIGN KEY (owner_id) REFERENCES fimi_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_media_tag ADD CONSTRAINT FK_5768A4A0EA9FDD75 FOREIGN KEY (media_id) REFERENCES fimi_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_media_tag ADD CONSTRAINT FK_5768A4A0BAD26311 FOREIGN KEY (tag_id) REFERENCES fimi_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_variant ADD CONSTRAINT FK_A3D39EBC166D1F9C FOREIGN KEY (project_id) REFERENCES fimi_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_variant_media ADD CONSTRAINT FK_CF5F2A13B69A9AF FOREIGN KEY (variant_id) REFERENCES fimi_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_variant_media ADD CONSTRAINT FK_CF5F2A1EA9FDD75 FOREIGN KEY (media_id) REFERENCES fimi_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_media DROP CONSTRAINT FK_CBA57BD17E3C61F9');
        $this->addSql('ALTER TABLE fimi_media_tag DROP CONSTRAINT FK_5768A4A0EA9FDD75');
        $this->addSql('ALTER TABLE fimi_media_tag DROP CONSTRAINT FK_5768A4A0BAD26311');
        $this->addSql('ALTER TABLE fimi_variant DROP CONSTRAINT FK_A3D39EBC166D1F9C');
        $this->addSql('ALTER TABLE fimi_variant_media DROP CONSTRAINT FK_CF5F2A13B69A9AF');
        $this->addSql('ALTER TABLE fimi_variant_media DROP CONSTRAINT FK_CF5F2A1EA9FDD75');
        $this->addSql('DROP TABLE fimi_media');
        $this->addSql('DROP TABLE fimi_media_tag');
        $this->addSql('DROP TABLE fimi_tag');
        $this->addSql('DROP TABLE fimi_variant');
        $this->addSql('DROP TABLE fimi_variant_media');
    }
}
