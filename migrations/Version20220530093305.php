<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530093305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE master_program_training_centers (master_program_id INT NOT NULL, training_centers_id INT NOT NULL, PRIMARY KEY(master_program_id, training_centers_id))');
        $this->addSql('CREATE INDEX IDX_28FBF7DB36A6A584 ON master_program_training_centers (master_program_id)');
        $this->addSql('CREATE INDEX IDX_28FBF7DB93F7D12F ON master_program_training_centers (training_centers_id)');
        $this->addSql('ALTER TABLE master_program_training_centers ADD CONSTRAINT FK_28FBF7DB36A6A584 FOREIGN KEY (master_program_id) REFERENCES master_program (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE master_program_training_centers ADD CONSTRAINT FK_28FBF7DB93F7D12F FOREIGN KEY (training_centers_id) REFERENCES training_centers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE master_program_training_centers');
    }
}
