<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717093628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborator ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE collaborator ADD CONSTRAINT FK_606D487C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_606D487C9D86650F ON collaborator (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaborator DROP FOREIGN KEY FK_606D487C9D86650F');
        $this->addSql('DROP INDEX UNIQ_606D487C9D86650F ON collaborator');
        $this->addSql('ALTER TABLE collaborator DROP user_id_id');
    }
}
