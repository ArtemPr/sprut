<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531134542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE training_centers_id_seq CASCADE');
        $this->addSql('ALTER TABLE training_centers ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers ADD url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE training_centers ADD external_upload_bakalavrmagistr_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE training_centers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE training_centers DROP phone');
        $this->addSql('ALTER TABLE training_centers DROP email');
        $this->addSql('ALTER TABLE training_centers DROP url');
        $this->addSql('ALTER TABLE training_centers DROP external_upload_bakalavrmagistr_id');
    }
}
