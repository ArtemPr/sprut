<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607075539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prof_standarts_activities ADD prof_standart_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prof_standarts_activities ADD CONSTRAINT FK_341B56251732AD00 FOREIGN KEY (prof_standart_id_id) REFERENCES prof_standarts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_341B56251732AD00 ON prof_standarts_activities (prof_standart_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE prof_standarts_activities DROP CONSTRAINT FK_341B56251732AD00');
        $this->addSql('DROP INDEX IDX_341B56251732AD00');
        $this->addSql('ALTER TABLE prof_standarts_activities DROP prof_standart_id_id');
    }
}
