<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828214102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_variant_prompt (id UUID NOT NULL, variant_id UUID DEFAULT NULL, prompt TEXT NOT NULL, prompt_meta JSON DEFAULT NULL, prompt_template TEXT DEFAULT NULL, is_done BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAEE6A3E3B69A9AF ON fimi_variant_prompt (variant_id)');
        $this->addSql('COMMENT ON COLUMN fimi_variant_prompt.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_variant_prompt.variant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD CONSTRAINT FK_CAEE6A3E3B69A9AF FOREIGN KEY (variant_id) REFERENCES fimi_variant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ADD is_visible BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP CONSTRAINT FK_CAEE6A3E3B69A9AF');
        $this->addSql('DROP TABLE fimi_variant_prompt');
        $this->addSql('ALTER TABLE fimi_variant DROP is_visible');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
    }
}
