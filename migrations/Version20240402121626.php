<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402121626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department_city_to_add (territoire_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_F7445246D0F97A8 (territoire_id), INDEX IDX_F74452468BAC62AF (city_id), PRIMARY KEY(territoire_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department_city_to_remove (territoire_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_B076437DD0F97A8 (territoire_id), INDEX IDX_B076437D8BAC62AF (city_id), PRIMARY KEY(territoire_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department_city_to_add ADD CONSTRAINT FK_F7445246D0F97A8 FOREIGN KEY (territoire_id) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department_city_to_add ADD CONSTRAINT FK_F74452468BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department_city_to_remove ADD CONSTRAINT FK_B076437DD0F97A8 FOREIGN KEY (territoire_id) REFERENCES territoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department_city_to_remove ADD CONSTRAINT FK_B076437D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department_city_to_add DROP FOREIGN KEY FK_F7445246D0F97A8');
        $this->addSql('ALTER TABLE department_city_to_add DROP FOREIGN KEY FK_F74452468BAC62AF');
        $this->addSql('ALTER TABLE department_city_to_remove DROP FOREIGN KEY FK_B076437DD0F97A8');
        $this->addSql('ALTER TABLE department_city_to_remove DROP FOREIGN KEY FK_B076437D8BAC62AF');
        $this->addSql('DROP TABLE department_city_to_add');
        $this->addSql('DROP TABLE department_city_to_remove');
    }
}
