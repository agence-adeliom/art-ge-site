<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124133716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE territoire_tourism (territoire_source INT NOT NULL, territoire_target INT NOT NULL, INDEX IDX_F4CD0E3D5EF713E (territoire_source), INDEX IDX_F4CD0E3D1C0A21B1 (territoire_target), PRIMARY KEY(territoire_source, territoire_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE territoire_tourism ADD CONSTRAINT FK_F4CD0E3D5EF713E FOREIGN KEY (territoire_source) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_tourism ADD CONSTRAINT FK_F4CD0E3D1C0A21B1 FOREIGN KEY (territoire_target) REFERENCES territoire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE territoire_tourism DROP FOREIGN KEY FK_F4CD0E3D5EF713E');
        $this->addSql('ALTER TABLE territoire_tourism DROP FOREIGN KEY FK_F4CD0E3D1C0A21B1');
        $this->addSql('DROP TABLE territoire_tourism');
    }
}
