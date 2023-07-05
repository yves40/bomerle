<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705164125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes ADD published TINYINT(1) DEFAULT NULL, CHANGE stock stock INT NOT NULL, CHANGE weight weight INT NOT NULL, CHANGE lenght lenght INT NOT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes DROP published, CHANGE stock stock INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE lenght lenght INT DEFAULT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT DEFAULT NULL');
    }
}
