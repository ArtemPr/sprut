<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615071724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departament DROP CONSTRAINT fk_34f6fda3233631bd');
        $this->addSql('DROP INDEX idx_34f6fda3233631bd');
        $this->addSql('ALTER TABLE departament DROP user_departament_id');
        $this->addSql('ALTER TABLE "user" ADD departament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64948B3EEE4 FOREIGN KEY (departament_id) REFERENCES departament (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64948B3EEE4 ON "user" (departament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64948B3EEE4');
        $this->addSql('DROP INDEX IDX_8D93D64948B3EEE4');
        $this->addSql('ALTER TABLE "user" DROP departament_id');
        $this->addSql('ALTER TABLE departament ADD user_departament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE departament ADD CONSTRAINT fk_34f6fda3233631bd FOREIGN KEY (user_departament_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_34f6fda3233631bd ON departament (user_departament_id)');
    }
}
