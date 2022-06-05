<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602143147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE federal_standart_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE federal_standart_competencies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE federal_standart_competencies (id INT NOT NULL, code INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE federal_standart ADD federal_standart_competencies_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE federal_standart ADD CONSTRAINT FK_2606196129BE0A4F FOREIGN KEY (federal_standart_competencies_id) REFERENCES federal_standart_competencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2606196129BE0A4F ON federal_standart (federal_standart_competencies_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE federal_standart DROP CONSTRAINT FK_2606196129BE0A4F');
        $this->addSql('DROP SEQUENCE federal_standart_competencies_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE federal_standart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE federal_standart_competencies');
        $this->addSql('DROP INDEX IDX_2606196129BE0A4F');
        $this->addSql('ALTER TABLE federal_standart DROP federal_standart_competencies_id');
    }
}
