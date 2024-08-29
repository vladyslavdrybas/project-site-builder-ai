<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240828135926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ADD prompt_meta JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_variant ADD prompt_template TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_variant ADD is_ai_made BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_variant DROP prompt_meta');
        $this->addSql('ALTER TABLE fimi_variant DROP prompt_template');
        $this->addSql('ALTER TABLE fimi_variant DROP is_ai_made');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
    }
}
