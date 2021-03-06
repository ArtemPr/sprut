<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615080324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D6498BAC62AF ON "user" (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6498BAC62AF');
        $this->addSql('DROP INDEX IDX_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP city_id');
    }
}
