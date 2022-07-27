<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726145400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE document_templates_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE subdivisions_id_seq CASCADE');
        $this->addSql('CREATE TABLE training_centers_master_program (training_centers_id INT NOT NULL, master_program_id INT NOT NULL, PRIMARY KEY(training_centers_id, master_program_id))');
        $this->addSql('CREATE INDEX IDX_AD997F0C93F7D12F ON training_centers_master_program (training_centers_id)');
        $this->addSql('CREATE INDEX IDX_AD997F0C36A6A584 ON training_centers_master_program (master_program_id)');
        $this->addSql('ALTER TABLE training_centers_master_program ADD CONSTRAINT FK_AD997F0C93F7D12F FOREIGN KEY (training_centers_id) REFERENCES training_centers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE training_centers_master_program ADD CONSTRAINT FK_AD997F0C36A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE document_templates');
        $this->addSql('DROP TABLE cluster');
        $this->addSql('DROP TABLE subdivisions');
        $this->addSql('ALTER TABLE employer_requirements DROP delete');
        $this->addSql('ALTER TABLE potential_jobs DROP delete');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE document_templates_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE subdivisions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE document_templates (id INT NOT NULL, active BOOLEAN NOT NULL, template_name VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, date_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author VARCHAR(255) NOT NULL, delete BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cluster (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE subdivisions (id INT NOT NULL, subdivisions_name VARCHAR(255) NOT NULL, delete BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE training_centers_master_program');
        $this->addSql('ALTER TABLE employer_requirements ADD delete BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE potential_jobs ADD delete BOOLEAN DEFAULT NULL');
    }
}
