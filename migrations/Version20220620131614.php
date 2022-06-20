<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620131614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kaferda ADD training_centre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE kaferda ADD CONSTRAINT FK_A1AEDD1128B0B437 FOREIGN KEY (training_centre_id) REFERENCES training_centers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A1AEDD1128B0B437 ON kaferda (training_centre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kaferda DROP CONSTRAINT FK_A1AEDD1128B0B437');
        $this->addSql('DROP INDEX IDX_A1AEDD1128B0B437');
        $this->addSql('ALTER TABLE kaferda DROP training_centre_id');
    }
}
