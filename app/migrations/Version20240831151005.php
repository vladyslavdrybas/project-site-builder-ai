<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831151005 extends AbstractMigration
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
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD request_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD execution_time INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP request_at');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP execution_time');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
    }
}
