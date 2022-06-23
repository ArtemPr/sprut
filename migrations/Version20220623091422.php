<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623091422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE master_program_federal_standart (master_program_id INT NOT NULL, federal_standart_id INT NOT NULL, PRIMARY KEY(master_program_id, federal_standart_id))');
        $this->addSql('CREATE INDEX IDX_F48EA58536A6A584 ON master_program_federal_standart (master_program_id)');
        $this->addSql('CREATE INDEX IDX_F48EA5855D37682C ON master_program_federal_standart (federal_standart_id)');
        $this->addSql('ALTER TABLE master_program_federal_standart ADD CONSTRAINT FK_F48EA58536A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_federal_standart ADD CONSTRAINT FK_F48EA5855D37682C FOREIGN KEY (federal_standart_id) REFERENCES federal_standart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE master_program_federal_standart');
    }
}
