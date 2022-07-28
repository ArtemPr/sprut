<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728131430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kaferda ADD product_line_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE kaferda ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE kaferda ADD CONSTRAINT FK_A1AEDD119CA26EF2 FOREIGN KEY (product_line_id) REFERENCES product_line (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A1AEDD119CA26EF2 ON kaferda (product_line_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kaferda DROP CONSTRAINT FK_A1AEDD119CA26EF2');
        $this->addSql('DROP INDEX IDX_A1AEDD119CA26EF2');
        $this->addSql('ALTER TABLE kaferda DROP product_line_id');
        $this->addSql('ALTER TABLE kaferda DROP parent_id');
    }
}
