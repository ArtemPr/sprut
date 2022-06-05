<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605161437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE federal_standart DROP CONSTRAINT fk_2606196129be0a4f');
        $this->addSql('DROP INDEX idx_2606196129be0a4f');
        $this->addSql('ALTER TABLE federal_standart DROP federal_standart_competencies_id');
        $this->addSql('ALTER TABLE federal_standart_competencies ADD federal_standart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE federal_standart_competencies ADD CONSTRAINT FK_C0F243B35D37682C FOREIGN KEY (federal_standart_id) REFERENCES federal_standart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C0F243B35D37682C ON federal_standart_competencies (federal_standart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE federal_standart ADD federal_standart_competencies_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE federal_standart ADD CONSTRAINT fk_2606196129be0a4f FOREIGN KEY (federal_standart_competencies_id) REFERENCES federal_standart_competencies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2606196129be0a4f ON federal_standart (federal_standart_competencies_id)');
        $this->addSql('ALTER TABLE federal_standart_competencies DROP CONSTRAINT FK_C0F243B35D37682C');
        $this->addSql('DROP INDEX IDX_C0F243B35D37682C');
        $this->addSql('ALTER TABLE federal_standart_competencies DROP federal_standart_id');
    }
}
