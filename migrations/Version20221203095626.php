<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203095626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact CHANGE knife reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638B83297E7 FOREIGN KEY (reservation_id) REFERENCES knifes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638B83297E7 ON contact (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638B83297E7');
        $this->addSql('DROP INDEX UNIQ_4C62E638B83297E7 ON contact');
        $this->addSql('ALTER TABLE contact CHANGE reservation_id knife INT DEFAULT NULL');
    }
}
