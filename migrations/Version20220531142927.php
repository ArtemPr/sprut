<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531142927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE training_centers_requisites_id_seq CASCADE');
        $this->addSql('ALTER TABLE training_centers DROP CONSTRAINT fk_fa734b3f97d5a932');
        $this->addSql('DROP INDEX idx_fa734b3f97d5a932');
        $this->addSql('ALTER TABLE training_centers DROP training_centers_requisites_id');
        $this->addSql('ALTER TABLE training_centers_requisites ADD training_centre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers_requisites ADD CONSTRAINT FK_95B7D03128B0B437 FOREIGN KEY (training_centre_id) REFERENCES training_centers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_95B7D03128B0B437 ON training_centers_requisites (training_centre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE training_centers_requisites_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE training_centers_requisites DROP CONSTRAINT FK_95B7D03128B0B437');
        $this->addSql('DROP INDEX IDX_95B7D03128B0B437');
        $this->addSql('ALTER TABLE training_centers_requisites DROP training_centre_id');
        $this->addSql('ALTER TABLE training_centers ADD training_centers_requisites_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers ADD CONSTRAINT fk_fa734b3f97d5a932 FOREIGN KEY (training_centers_requisites_id) REFERENCES training_centers_requisites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fa734b3f97d5a932 ON training_centers (training_centers_requisites_id)');
    }
}
