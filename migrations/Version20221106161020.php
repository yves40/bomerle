<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106161020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knifes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, stock INT NOT NULL, wheight INT NOT NULL, lenght INT NOT NULL, close_lenght INT DEFAULT NULL, cuttingedge_lenght INT NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_2E01276912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knifes ADD CONSTRAINT FK_2E01276912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes DROP FOREIGN KEY FK_2E01276912469DE2');
        $this->addSql('DROP TABLE knifes');
    }
}
