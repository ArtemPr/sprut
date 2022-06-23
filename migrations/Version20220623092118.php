<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623092118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE master_program_federal_standart_competencies (master_program_id INT NOT NULL, federal_standart_competencies_id INT NOT NULL, PRIMARY KEY(master_program_id, federal_standart_competencies_id))');
        $this->addSql('CREATE INDEX IDX_49F049A736A6A584 ON master_program_federal_standart_competencies (master_program_id)');
        $this->addSql('CREATE INDEX IDX_49F049A729BE0A4F ON master_program_federal_standart_competencies (federal_standart_competencies_id)');
        $this->addSql('ALTER TABLE master_program_federal_standart_competencies ADD CONSTRAINT FK_49F049A736A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_federal_standart_competencies ADD CONSTRAINT FK_49F049A729BE0A4F FOREIGN KEY (federal_standart_competencies_id) REFERENCES federal_standart_competencies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE master_program_federal_standart_competencies');
    }
}
