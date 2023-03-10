<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310074720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638B83297E7 FOREIGN KEY (reservation_id) REFERENCES knifes (id)');
        $this->addSql('ALTER TABLE dblog ADD useremail VARCHAR(64) DEFAULT NULL');
        // $this->addSql('ALTER TABLE requests_tracker CHANGE email email VARCHAR(64) NOT NULL');
        // $this->addSql('ALTER TABLE users DROP selector');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638B83297E7');
        $this->addSql('ALTER TABLE dblog DROP useremail');
        $this->addSql('ALTER TABLE requests_tracker CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD selector LONGTEXT DEFAULT NULL');
    }
}
