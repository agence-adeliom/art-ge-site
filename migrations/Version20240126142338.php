<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126142338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse_choice (reponse_id INT NOT NULL, choice_id INT NOT NULL, INDEX IDX_15FF830BCF18BB82 (reponse_id), INDEX IDX_15FF830B998666D1 (choice_id), PRIMARY KEY(reponse_id, choice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse_choice ADD CONSTRAINT FK_15FF830BCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_choice ADD CONSTRAINT FK_15FF830B998666D1 FOREIGN KEY (choice_id) REFERENCES choice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse_choice DROP FOREIGN KEY FK_15FF830BCF18BB82');
        $this->addSql('ALTER TABLE reponse_choice DROP FOREIGN KEY FK_15FF830B998666D1');
        $this->addSql('DROP TABLE reponse_choice');
    }
}
