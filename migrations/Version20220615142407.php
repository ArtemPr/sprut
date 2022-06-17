<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615142407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD comment VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE module ADD purpose TEXT NOT NULL');
        $this->addSql('ALTER TABLE module ADD practice TEXT NOT NULL');
        $this->addSql('ALTER TABLE module ADD docx_old_doc_file_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE module ADD status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD active BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE module DROP comment');
        $this->addSql('ALTER TABLE module DROP purpose');
        $this->addSql('ALTER TABLE module DROP practice');
        $this->addSql('ALTER TABLE module DROP docx_old_doc_file_name');
        $this->addSql('ALTER TABLE module DROP status');
    }
}
