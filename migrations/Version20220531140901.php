<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531140901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE training_centers_requisites_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE training_centers_requisites (id INT NOT NULL, director VARCHAR(255) NOT NULL, director_position VARCHAR(255) NOT NULL, from_date DATE DEFAULT NULL, short_name TEXT DEFAULT NULL, full_name TEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, address TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE training_centers ADD training_centers_requisites_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers ADD CONSTRAINT FK_FA734B3F97D5A932 FOREIGN KEY (training_centers_requisites_id) REFERENCES training_centers_requisites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FA734B3F97D5A932 ON training_centers (training_centers_requisites_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE training_centers DROP CONSTRAINT FK_FA734B3F97D5A932');
        $this->addSql('DROP SEQUENCE training_centers_requisites_id_seq CASCADE');
        $this->addSql('DROP TABLE training_centers_requisites');
        $this->addSql('DROP INDEX IDX_FA734B3F97D5A932');
        $this->addSql('ALTER TABLE training_centers DROP training_centers_requisites_id');
    }
}
