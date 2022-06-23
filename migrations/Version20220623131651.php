<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623131651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE master_program_prof_standarts (master_program_id INT NOT NULL, prof_standarts_id INT NOT NULL, PRIMARY KEY(master_program_id, prof_standarts_id))');
        $this->addSql('CREATE INDEX IDX_D3E4A77836A6A584 ON master_program_prof_standarts (master_program_id)');
        $this->addSql('CREATE INDEX IDX_D3E4A778DD375E99 ON master_program_prof_standarts (prof_standarts_id)');
        $this->addSql('ALTER TABLE master_program_prof_standarts ADD CONSTRAINT FK_D3E4A77836A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_prof_standarts ADD CONSTRAINT FK_D3E4A778DD375E99 FOREIGN KEY (prof_standarts_id) REFERENCES prof_standarts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE master_program_prof_standarts');
    }
}
