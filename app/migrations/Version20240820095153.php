<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820095153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_project ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE fimi_project ADD goal TEXT NOT NULL');
        $this->addSql('ALTER TABLE fimi_project ADD start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE fimi_project ADD end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project DROP description');
        $this->addSql('ALTER TABLE fimi_project DROP goal');
        $this->addSql('ALTER TABLE fimi_project DROP start_at');
        $this->addSql('ALTER TABLE fimi_project DROP end_at');
    }
}
