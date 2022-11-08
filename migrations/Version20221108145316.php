<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108145316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE requests_tracker CHANGE requestactiontype requestactiontype VARCHAR(32) NOT NULL, CHANGE email email VARCHAR(64) NOT NULL, CHANGE selector selector LONGTEXT NOT NULL, CHANGE token token LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE users ADD confirmed DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE requests_tracker CHANGE requestactiontype requestactiontype VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE email email VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE selector selector TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE token token LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE users DROP confirmed');
    }
}
