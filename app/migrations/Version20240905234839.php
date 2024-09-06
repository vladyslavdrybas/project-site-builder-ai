<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905234839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_media_ai_prompt ADD width INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt ADD height INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt ADD mime_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt ADD url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt DROP width');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt DROP height');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt DROP mime_type');
        $this->addSql('ALTER TABLE fimi_media_ai_prompt DROP url');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
    }
}
