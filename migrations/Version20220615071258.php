<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615071258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departament ADD user_departament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE departament ADD CONSTRAINT FK_34F6FDA3233631BD FOREIGN KEY (user_departament_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_34F6FDA3233631BD ON departament (user_departament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE departament DROP CONSTRAINT FK_34F6FDA3233631BD');
        $this->addSql('DROP INDEX IDX_34F6FDA3233631BD');
        $this->addSql('ALTER TABLE departament DROP user_departament_id');
    }
}
