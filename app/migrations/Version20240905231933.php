<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905231933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_media_ai_prompt (id UUID NOT NULL, owner_id UUID DEFAULT NULL, prompt TEXT NOT NULL, api_name VARCHAR(255) DEFAULT NULL, model_name VARCHAR(255) DEFAULT NULL, prompt_meta JSON DEFAULT NULL, prompt_answer JSON DEFAULT NULL, is_done BOOLEAN DEFAULT false NOT NULL, request_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, execution_milliseconds INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C70B54E77E3C61F9 ON fimi_media_ai_prompt (owner_id)');
        $this->addSql('COMMENT ON COLUMN fimi_media_ai_prompt.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_media_ai_prompt.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fimi_media_ai_prompt.execution_milliseconds IS \'milliseconds\'');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt ADD CONSTRAINT FK_C70B54E77E3C61F9 FOREIGN KEY (owner_id) REFERENCES fimi_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt DROP CONSTRAINT FK_C70B54E77E3C61F9');
        $this->addSql('DROP TABLE fimi_media_ai_prompt');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
    }
}
