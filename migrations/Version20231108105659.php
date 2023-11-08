<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108105659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE choice (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_C1AB5A921E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choice_typologie (id INT AUTO_INCREMENT NOT NULL, choice_id INT NOT NULL, typologie_id INT NOT NULL, restauration TINYINT(1) NOT NULL, ponderation INT NOT NULL, INDEX IDX_E498D929998666D1 (choice_id), INDEX IDX_E498D92942F4634A (typologie_id), INDEX idx_choice_typologie_restauration (choice_id, typologie_id, restauration), INDEX idx_typologie_restauration (typologie_id, restauration), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, thematique_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B6F7494E476556AF (thematique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repondant (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, typologie_id INT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, restauration TINYINT(1) NOT NULL, green_space TINYINT(1) NOT NULL, INDEX IDX_C2D8C7E5AE80F5DF (department_id), INDEX IDX_C2D8C7E542F4634A (typologie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, repondant_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'Date du commencement du formulaire(DC2Type:datetime_immutable)\', submitted_at DATETIME DEFAULT NULL COMMENT \'Date de la soumission du formulaire(DC2Type:datetime_immutable)\', points DOUBLE PRECISION DEFAULT NULL COMMENT \'Somme des points obtenus\', total INT NOT NULL COMMENT \'Somme des points possible d\'\'obtenir\', raw_form JSON NOT NULL COMMENT \'(DC2Type:json)\', processed_form JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_5FB6DEC7C5DBCCD6 (repondant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_choice (reponse_id INT NOT NULL, choice_id INT NOT NULL, INDEX IDX_15FF830BCF18BB82 (reponse_id), INDEX IDX_15FF830B998666D1 (choice_id), PRIMARY KEY(reponse_id, choice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, reponse_id INT NOT NULL, thematique_id INT NOT NULL, points INT NOT NULL COMMENT \'Somme des points obtenus\', total INT NOT NULL COMMENT \'Somme des points possible d\'\'obtenir\', INDEX IDX_32993751CF18BB82 (reponse_id), INDEX IDX_32993751476556AF (thematique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE territoire (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, zips JSON NOT NULL COMMENT \'(DC2Type:json)\', code VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, use_slug TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thematique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typologie (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choice ADD CONSTRAINT FK_C1AB5A921E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE choice_typologie ADD CONSTRAINT FK_E498D929998666D1 FOREIGN KEY (choice_id) REFERENCES choice (id)');
        $this->addSql('ALTER TABLE choice_typologie ADD CONSTRAINT FK_E498D92942F4634A FOREIGN KEY (typologie_id) REFERENCES typologie (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E476556AF FOREIGN KEY (thematique_id) REFERENCES thematique (id)');
        $this->addSql('ALTER TABLE repondant ADD CONSTRAINT FK_C2D8C7E5AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE repondant ADD CONSTRAINT FK_C2D8C7E542F4634A FOREIGN KEY (typologie_id) REFERENCES typologie (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7C5DBCCD6 FOREIGN KEY (repondant_id) REFERENCES repondant (id)');
        $this->addSql('ALTER TABLE reponse_choice ADD CONSTRAINT FK_15FF830BCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_choice ADD CONSTRAINT FK_15FF830B998666D1 FOREIGN KEY (choice_id) REFERENCES choice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751476556AF FOREIGN KEY (thematique_id) REFERENCES thematique (id)');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choice DROP FOREIGN KEY FK_C1AB5A921E27F6BF');
        $this->addSql('ALTER TABLE choice_typologie DROP FOREIGN KEY FK_E498D929998666D1');
        $this->addSql('ALTER TABLE choice_typologie DROP FOREIGN KEY FK_E498D92942F4634A');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E476556AF');
        $this->addSql('ALTER TABLE repondant DROP FOREIGN KEY FK_C2D8C7E5AE80F5DF');
        $this->addSql('ALTER TABLE repondant DROP FOREIGN KEY FK_C2D8C7E542F4634A');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7C5DBCCD6');
        $this->addSql('ALTER TABLE reponse_choice DROP FOREIGN KEY FK_15FF830BCF18BB82');
        $this->addSql('ALTER TABLE reponse_choice DROP FOREIGN KEY FK_15FF830B998666D1');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751CF18BB82');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751476556AF');
        $this->addSql('DROP TABLE choice');
        $this->addSql('DROP TABLE choice_typologie');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE repondant');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_choice');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE territoire');
        $this->addSql('DROP TABLE thematique');
        $this->addSql('DROP TABLE typologie');
        $this->addSql('ALTER TABLE easy_admin__user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
