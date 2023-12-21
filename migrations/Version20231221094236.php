<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221094236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE epci (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, siren VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE epci_city (epci_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_7F54CC0D4E7C18CB (epci_id), INDEX IDX_7F54CC0D8BAC62AF (city_id), PRIMARY KEY(epci_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE territoire_epci (territoire_id INT NOT NULL, epci_id INT NOT NULL, INDEX IDX_622B7A7AD0F97A8 (territoire_id), INDEX IDX_622B7A7A4E7C18CB (epci_id), PRIMARY KEY(territoire_id, epci_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE territoire_territoire (territoire_source INT NOT NULL, territoire_target INT NOT NULL, INDEX IDX_48B1D5B65EF713E (territoire_source), INDEX IDX_48B1D5B61C0A21B1 (territoire_target), PRIMARY KEY(territoire_source, territoire_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE epci_city ADD CONSTRAINT FK_7F54CC0D4E7C18CB FOREIGN KEY (epci_id) REFERENCES epci (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epci_city ADD CONSTRAINT FK_7F54CC0D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_epci ADD CONSTRAINT FK_622B7A7AD0F97A8 FOREIGN KEY (territoire_id) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_epci ADD CONSTRAINT FK_622B7A7A4E7C18CB FOREIGN KEY (epci_id) REFERENCES epci (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_territoire ADD CONSTRAINT FK_48B1D5B65EF713E FOREIGN KEY (territoire_source) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire_territoire ADD CONSTRAINT FK_48B1D5B61C0A21B1 FOREIGN KEY (territoire_target) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE territoire DROP FOREIGN KEY FK_B8655F54727ACA70');
        $this->addSql('DROP INDEX IDX_B8655F54727ACA70 ON territoire');
        $this->addSql('ALTER TABLE territoire DROP parent_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE epci_city DROP FOREIGN KEY FK_7F54CC0D4E7C18CB');
        $this->addSql('ALTER TABLE epci_city DROP FOREIGN KEY FK_7F54CC0D8BAC62AF');
        $this->addSql('ALTER TABLE territoire_epci DROP FOREIGN KEY FK_622B7A7AD0F97A8');
        $this->addSql('ALTER TABLE territoire_epci DROP FOREIGN KEY FK_622B7A7A4E7C18CB');
        $this->addSql('ALTER TABLE territoire_territoire DROP FOREIGN KEY FK_48B1D5B65EF713E');
        $this->addSql('ALTER TABLE territoire_territoire DROP FOREIGN KEY FK_48B1D5B61C0A21B1');
        $this->addSql('DROP TABLE epci');
        $this->addSql('DROP TABLE epci_city');
        $this->addSql('DROP TABLE territoire_epci');
        $this->addSql('DROP TABLE territoire_territoire');
        $this->addSql('ALTER TABLE territoire ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE territoire ADD CONSTRAINT FK_B8655F54727ACA70 FOREIGN KEY (parent_id) REFERENCES territoire (id)');
        $this->addSql('CREATE INDEX IDX_B8655F54727ACA70 ON territoire (parent_id)');
    }
}
