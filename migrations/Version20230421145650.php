<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230421145650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE knifes CHANGE stock stock INT NOT NULL, CHANGE weight weight INT NOT NULL, CHANGE lenght lenght INT NOT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT NOT NULL');
        // $this->addSql('ALTER TABLE slide_images RENAME INDEX idx_42f946ce1d87d6d TO IDX_42F946CE8B14E343');
        $this->addSql('ALTER TABLE slide_show ADD monday TINYINT(1) DEFAULT NULL, ADD tuesday TINYINT(1) DEFAULT NULL, ADD wednesday TINYINT(1) DEFAULT NULL, ADD thursday TINYINT(1) DEFAULT NULL, ADD friday TINYINT(1) DEFAULT NULL, ADD saturday TINYINT(1) DEFAULT NULL, ADD sunday TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE knifes CHANGE stock stock INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE lenght lenght INT DEFAULT NULL, CHANGE cuttingedge_lenght cuttingedge_lenght INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE slide_images RENAME INDEX idx_42f946ce8b14e343 TO IDX_42F946CE1D87D6D');
        $this->addSql('ALTER TABLE slide_show DROP monday, DROP tuesday, DROP wednesday, DROP thursday, DROP friday, DROP saturday, DROP sunday');
    }
}
