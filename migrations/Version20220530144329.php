<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530144329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE literature_category_id_seq CASCADE');
        $this->addSql('ALTER TABLE literature ADD type INT NOT NULL');
        $this->addSql('ALTER TABLE literature ADD authors TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE literature ADD year INT DEFAULT NULL');
        $this->addSql('ALTER TABLE literature ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE literature ADD link TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE literature ALTER name TYPE TEXT');
        $this->addSql('ALTER TABLE literature ALTER name DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE literature_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE literature DROP type');
        $this->addSql('ALTER TABLE literature DROP authors');
        $this->addSql('ALTER TABLE literature DROP year');
        $this->addSql('ALTER TABLE literature DROP description');
        $this->addSql('ALTER TABLE literature DROP link');
        $this->addSql('ALTER TABLE literature ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE literature ALTER name DROP DEFAULT');
    }
}
