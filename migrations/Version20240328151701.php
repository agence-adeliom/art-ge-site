<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328151701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE territoire_city (territoire_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_4C8329ABD0F97A8 (territoire_id), INDEX IDX_4C8329AB8BAC62AF (city_id), PRIMARY KEY(territoire_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE territoire_city ADD CONSTRAINT FK_4C8329ABD0F97A8 FOREIGN KEY (territoire_id) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_city ADD CONSTRAINT FK_4C8329AB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire DROP insees');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE territoire ADD insees JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE territoire_city DROP FOREIGN KEY FK_4C8329ABD0F97A8');
        $this->addSql('ALTER TABLE territoire_city DROP FOREIGN KEY FK_4C8329AB8BAC62AF');
        $this->addSql('DROP TABLE territoire_city');
    }
}
