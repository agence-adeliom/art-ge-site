<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102124400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE choice (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C1AB5A921E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, thematique_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B6F7494E476556AF (thematique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repondant (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, typologie_id INT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, restauration TINYINT(1) NOT NULL, green_spacee TINYINT(1) NOT NULL, INDEX IDX_C2D8C7E5AE80F5DF (department_id), INDEX IDX_C2D8C7E542F4634A (typologie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repondant_typologie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, repondant_id INT NOT NULL, completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', submitted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', score DOUBLE PRECISION DEFAULT NULL, form JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_5FB6DEC7C5DBCCD6 (repondant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thematique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choice ADD CONSTRAINT FK_C1AB5A921E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E476556AF FOREIGN KEY (thematique_id) REFERENCES thematique (id)');
        $this->addSql('ALTER TABLE repondant ADD CONSTRAINT FK_C2D8C7E5AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE repondant ADD CONSTRAINT FK_C2D8C7E542F4634A FOREIGN KEY (typologie_id) REFERENCES repondant_typologie (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7C5DBCCD6 FOREIGN KEY (repondant_id) REFERENCES repondant (id)');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choice DROP FOREIGN KEY FK_C1AB5A921E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E476556AF');
        $this->addSql('ALTER TABLE repondant DROP FOREIGN KEY FK_C2D8C7E5AE80F5DF');
        $this->addSql('ALTER TABLE repondant DROP FOREIGN KEY FK_C2D8C7E542F4634A');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7C5DBCCD6');
        $this->addSql('DROP TABLE choice');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE repondant');
        $this->addSql('DROP TABLE repondant_typologie');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE thematique');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
