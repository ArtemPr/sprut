<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727085505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE master_program_employer_requirements (master_program_id INT NOT NULL, employer_requirements_id INT NOT NULL, PRIMARY KEY(master_program_id, employer_requirements_id))');
        $this->addSql('CREATE INDEX IDX_8B2C61DE36A6A584 ON master_program_employer_requirements (master_program_id)');
        $this->addSql('CREATE INDEX IDX_8B2C61DE4EF15F47 ON master_program_employer_requirements (employer_requirements_id)');
        $this->addSql('CREATE TABLE master_program_potential_jobs (master_program_id INT NOT NULL, potential_jobs_id INT NOT NULL, PRIMARY KEY(master_program_id, potential_jobs_id))');
        $this->addSql('CREATE INDEX IDX_8417C47336A6A584 ON master_program_potential_jobs (master_program_id)');
        $this->addSql('CREATE INDEX IDX_8417C473BB062AED ON master_program_potential_jobs (potential_jobs_id)');
        $this->addSql('ALTER TABLE master_program_employer_requirements ADD CONSTRAINT FK_8B2C61DE36A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_employer_requirements ADD CONSTRAINT FK_8B2C61DE4EF15F47 FOREIGN KEY (employer_requirements_id) REFERENCES employer_requirements (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_potential_jobs ADD CONSTRAINT FK_8417C47336A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_potential_jobs ADD CONSTRAINT FK_8417C473BB062AED FOREIGN KEY (potential_jobs_id) REFERENCES potential_jobs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE master_program_employer_requirements');
        $this->addSql('DROP TABLE master_program_potential_jobs');
    }
}
