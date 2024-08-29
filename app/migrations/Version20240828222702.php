<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828222702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('DROP INDEX idx_caee6a3e3b69a9af');
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD prompt_answer JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAEE6A3E3B69A9AF ON fimi_variant_prompt (variant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('DROP INDEX UNIQ_CAEE6A3E3B69A9AF');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP prompt_answer');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('CREATE INDEX idx_caee6a3e3b69a9af ON fimi_variant_prompt (variant_id)');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
    }
}
