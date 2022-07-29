<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728125123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE master_program_training_centers');
        $this->addSql('ALTER TABLE master_program ADD active BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER INDEX idx_5cfc96579133fdd RENAME TO IDX_5CFC9657C36A3328');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE master_program_training_centers (master_program_id INT NOT NULL, training_centers_id INT NOT NULL, PRIMARY KEY(master_program_id, training_centers_id))');
        $this->addSql('CREATE INDEX idx_28fbf7db36a6a584 ON master_program_training_centers (master_program_id)');
        $this->addSql('CREATE INDEX idx_28fbf7db93f7d12f ON master_program_training_centers (training_centers_id)');
        $this->addSql('ALTER TABLE master_program_training_centers ADD CONSTRAINT fk_28fbf7db36a6a584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_training_centers ADD CONSTRAINT fk_28fbf7db93f7d12f FOREIGN KEY (training_centers_id) REFERENCES training_centers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program DROP active');
        $this->addSql('ALTER INDEX idx_5cfc9657c36a3328 RENAME TO idx_5cfc96579133fdd');
    }
}
