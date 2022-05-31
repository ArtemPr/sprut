<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531124731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE master_program ADD program_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE master_program DROP program_type');
        $this->addSql('ALTER TABLE master_program ADD CONSTRAINT FK_4ECA9AEC4EA67447 FOREIGN KEY (program_type_id) REFERENCES program_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4ECA9AEC4EA67447 ON master_program (program_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE master_program DROP CONSTRAINT FK_4ECA9AEC4EA67447');
        $this->addSql('DROP INDEX IDX_4ECA9AEC4EA67447');
        $this->addSql('ALTER TABLE master_program ADD program_type INT NOT NULL');
        $this->addSql('ALTER TABLE master_program DROP program_type_id');
    }
}
