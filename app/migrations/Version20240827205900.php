<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827205900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fimi_project_tag (project_id UUID NOT NULL, tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(project_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_B4FCCB8F166D1F9C ON fimi_project_tag (project_id)');
        $this->addSql('CREATE INDEX IDX_B4FCCB8FBAD26311 ON fimi_project_tag (tag_id)');
        $this->addSql('COMMENT ON COLUMN fimi_project_tag.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fimi_project_tag ADD CONSTRAINT FK_B4FCCB8F166D1F9C FOREIGN KEY (project_id) REFERENCES fimi_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_project_tag ADD CONSTRAINT FK_B4FCCB8FBAD26311 FOREIGN KEY (tag_id) REFERENCES fimi_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fimi_project ADD prompt_meta JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project DROP proposal');
        $this->addSql('ALTER TABLE fimi_project DROP customer_portrait');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project_tag DROP CONSTRAINT FK_B4FCCB8F166D1F9C');
        $this->addSql('ALTER TABLE fimi_project_tag DROP CONSTRAINT FK_B4FCCB8FBAD26311');
        $this->addSql('DROP TABLE fimi_project_tag');
        $this->addSql('ALTER TABLE fimi_project ADD proposal TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project ADD customer_portrait TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project DROP prompt_meta');
    }
}
