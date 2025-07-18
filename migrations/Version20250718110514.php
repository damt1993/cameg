<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718110514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD user_id_id INT DEFAULT NULL, ADD organisation_name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD contact VARCHAR(255) NOT NULL, ADD adress VARCHAR(255) NOT NULL, ADD manager_name VARCHAR(255) NOT NULL, ADD last_connected_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E099D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E099D86650F ON customer (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E099D86650F');
        $this->addSql('DROP INDEX UNIQ_81398E099D86650F ON customer');
        $this->addSql('ALTER TABLE customer DROP user_id_id, DROP organisation_name, DROP email, DROP contact, DROP adress, DROP manager_name, DROP last_connected_at');
    }
}
