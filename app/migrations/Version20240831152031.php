<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831152031 extends AbstractMigration
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
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD execution_milliseconds INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP execution_time');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('COMMENT ON COLUMN fimi_variant_prompt.execution_milliseconds IS \'milliseconds\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant_prompt ADD execution_time INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE fimi_variant_prompt DROP execution_milliseconds');
        $this->addSql('ALTER TABLE fimi_variant_prompt ALTER prompt_meta TYPE JSON');
        $this->addSql('ALTER TABLE fimi_variant ALTER prompt_meta TYPE JSON');
    }
}
