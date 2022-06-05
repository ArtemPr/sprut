<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601142848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(180)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER email SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(180)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
    }
}
