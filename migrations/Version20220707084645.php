<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707084645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE antiplagiat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE antiplagiat (id INT NOT NULL, discipline_id INT DEFAULT NULL, author_id INT DEFAULT NULL, status INT NOT NULL, file VARCHAR(255) NOT NULL, ыsize INT NOT NULL, data_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment TEXT DEFAULT NULL, plagiat_percent DOUBLE PRECISION DEFAULT NULL, result_file VARCHAR(255) DEFAULT NULL, result_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA6D74BFA5522701 ON antiplagiat (discipline_id)');
        $this->addSql('CREATE INDEX IDX_AA6D74BFF675F31B ON antiplagiat (author_id)');
        $this->addSql('ALTER TABLE antiplagiat ADD CONSTRAINT FK_AA6D74BFA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE antiplagiat ADD CONSTRAINT FK_AA6D74BFF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE antiplagiat_id_seq CASCADE');
        $this->addSql('DROP TABLE antiplagiat');
    }
}
