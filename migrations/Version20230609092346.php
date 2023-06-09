<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609092346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE knifes CHANGE stock stock INT NOT NULL, CHANGE weight weight INT NOT NULL, CHANGE lenght lenght INT NOT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT NOT NULL');
        $this->addSql('ALTER TABLE slide_show ADD timing SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE knifes CHANGE stock stock INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE lenght lenght INT DEFAULT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_show DROP timing');
    }
}
