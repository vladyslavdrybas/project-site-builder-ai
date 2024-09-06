<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240906004950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_media ADD media_ai_prompt UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN fimi_media.media_ai_prompt IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_media ADD CONSTRAINT FK_CBA57BD14165DD92 FOREIGN KEY (media_ai_prompt) REFERENCES fimi_media_ai_prompt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBA57BD14165DD92 ON fimi_media (media_ai_prompt)');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_media DROP CONSTRAINT FK_CBA57BD14165DD92');
        $this->addSql('DROP INDEX IDX_CBA57BD14165DD92');
        $this->addSql('ALTER TABLE fimi_media DROP media_ai_prompt');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
    }
}
