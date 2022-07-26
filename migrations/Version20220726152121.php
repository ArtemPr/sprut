<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726152121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE subdivisions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subdivisions (id INT NOT NULL, subdivisions_name VARCHAR(255) NOT NULL, delete BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE employer_requirements ADD delete BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE potential_jobs ADD delete BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE subdivisions_id_seq CASCADE');
        $this->addSql('DROP TABLE subdivisions');
        $this->addSql('ALTER TABLE employer_requirements DROP delete');
        $this->addSql('ALTER TABLE potential_jobs DROP delete');
    }
}
