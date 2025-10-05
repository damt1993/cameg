<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003204954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD ordered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP quantity, DROP price, DROP public_price, DROP perompt_at, CHANGE name order_number VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD quantity INT NOT NULL, ADD price INT NOT NULL, ADD public_price INT NOT NULL, ADD perompt_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP ordered_at, CHANGE order_number name VARCHAR(255) NOT NULL');
    }
}
