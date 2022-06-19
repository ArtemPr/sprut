<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619102956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE loger_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE loger (id INT NOT NULL, user_loger_id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12BCD8536300017B ON loger (user_loger_id)');
        $this->addSql('ALTER TABLE loger ADD CONSTRAINT FK_12BCD8536300017B FOREIGN KEY (user_loger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE loger_id_seq CASCADE');
        $this->addSql('DROP TABLE loger');
    }
}
