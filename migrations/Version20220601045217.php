<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601045217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_centers_requisites DROP CONSTRAINT fk_95b7d03128b0b437');
        $this->addSql('DROP INDEX idx_95b7d03128b0b437');
        $this->addSql('ALTER TABLE training_centers_requisites DROP training_centre_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE training_centers_requisites ADD training_centre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers_requisites ADD CONSTRAINT fk_95b7d03128b0b437 FOREIGN KEY (training_centre_id) REFERENCES training_centers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_95b7d03128b0b437 ON training_centers_requisites (training_centre_id)');
    }
}
