<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720071825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE litera_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE litera (id INT NOT NULL, discipline_id INT DEFAULT NULL, author_id INT NOT NULL, status INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, data_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment TEXT DEFAULT NULL, doc_id INT DEFAULT NULL, doc_name VARCHAR(255) DEFAULT NULL, doc_title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D72B0F04A5522701 ON litera (discipline_id)');
        $this->addSql('CREATE INDEX IDX_D72B0F04F675F31B ON litera (author_id)');
        $this->addSql('ALTER TABLE litera ADD CONSTRAINT FK_D72B0F04A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE litera ADD CONSTRAINT FK_D72B0F04F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE litera_id_seq CASCADE');
        $this->addSql('DROP TABLE litera');
    }
}
