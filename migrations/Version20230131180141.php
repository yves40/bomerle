<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131180141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, object VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4C62E638B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638B83297E7 FOREIGN KEY (reservation_id) REFERENCES knifes (id)');
        $this->addSql('ALTER TABLE images ADD rank SMALLINT DEFAULT NULL');
        // $this->addSql('ALTER TABLE users CHANGE role role JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638B83297E7');
        $this->addSql('DROP TABLE contact');
        $this->addSql('ALTER TABLE images DROP rank');
        $this->addSql('ALTER TABLE users CHANGE role role LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
