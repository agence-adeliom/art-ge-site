<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103084954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choice_typologie DROP green_space');
        $this->addSql('CREATE INDEX IDX_E498D929998666D142F4634A898B1EF1 ON choice_typologie (choice_id, typologie_id, restauration)');
        $this->addSql('CREATE INDEX IDX_E498D92942F4634A898B1EF1 ON choice_typologie (typologie_id, restauration)');
        $this->addSql('ALTER TABLE score CHANGE points points INT NOT NULL COMMENT \'Somme des points obtenus\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_E498D929998666D142F4634A898B1EF1 ON choice_typologie');
        $this->addSql('DROP INDEX IDX_E498D92942F4634A898B1EF1 ON choice_typologie');
        $this->addSql('ALTER TABLE choice_typologie ADD green_space TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE score CHANGE points points DOUBLE PRECISION NOT NULL COMMENT \'Somme des points obtenus\'');
    }
}
