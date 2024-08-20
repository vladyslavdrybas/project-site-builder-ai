<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820101623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fimi_project ADD proposal TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project ADD customer_portrait TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE fimi_project DROP goal');
        $this->addSql('ALTER TABLE fimi_project ALTER start_at DROP NOT NULL');
        $this->addSql('ALTER TABLE fimi_project ALTER end_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fimi_project ADD goal TEXT NOT NULL');
        $this->addSql('ALTER TABLE fimi_project DROP proposal');
        $this->addSql('ALTER TABLE fimi_project DROP customer_portrait');
        $this->addSql('ALTER TABLE fimi_project ALTER start_at SET NOT NULL');
        $this->addSql('ALTER TABLE fimi_project ALTER end_at SET NOT NULL');
    }
}
