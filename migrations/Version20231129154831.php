<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129154831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE territoire ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE territoire ADD CONSTRAINT FK_B8655F54727ACA70 FOREIGN KEY (parent_id) REFERENCES territoire (id)');
        $this->addSql('CREATE INDEX IDX_B8655F54727ACA70 ON territoire (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE territoire DROP FOREIGN KEY FK_B8655F54727ACA70');
        $this->addSql('DROP INDEX IDX_B8655F54727ACA70 ON territoire');
        $this->addSql('ALTER TABLE territoire DROP parent_id');
    }
}
