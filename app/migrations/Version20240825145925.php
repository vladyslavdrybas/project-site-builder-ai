<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240825145925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_media ADD path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fimi_media ADD server_alias VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fimi_media DROP content');
        $this->addSql('ALTER TABLE fimi_media ALTER version TYPE SMALLINT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_media ADD content BYTEA NOT NULL');
        $this->addSql('ALTER TABLE fimi_media DROP path');
        $this->addSql('ALTER TABLE fimi_media DROP server_alias');
        $this->addSql('ALTER TABLE fimi_media ALTER version TYPE INT');
    }
}
