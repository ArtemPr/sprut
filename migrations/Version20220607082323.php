<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607082323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prof_standarts_competences_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prof_standarts_competences (id INT NOT NULL, profstandart_activities_id INT DEFAULT NULL, name TEXT NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40C55F8799A1C7B5 ON prof_standarts_competences (profstandart_activities_id)');
        $this->addSql('ALTER TABLE prof_standarts_competences ADD CONSTRAINT FK_40C55F8799A1C7B5 FOREIGN KEY (profstandart_activities_id) REFERENCES prof_standarts_activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prof_standarts_competences_id_seq CASCADE');
        $this->addSql('DROP TABLE prof_standarts_competences');
    }
}
