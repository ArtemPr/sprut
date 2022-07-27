<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726153647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE document_templates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE document_templates (id INT NOT NULL, active BOOLEAN NOT NULL, template_name VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, date_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author VARCHAR(255) NOT NULL, delete BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cluster (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE document_templates_id_seq CASCADE');
        $this->addSql('DROP TABLE document_templates');
    }
}
