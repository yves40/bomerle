<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107170142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes ADD mechanism_id INT NOT NULL');
        $this->addSql('ALTER TABLE knifes ADD CONSTRAINT FK_2E01276937CD6DD0 FOREIGN KEY (mechanism_id) REFERENCES mechanism (id)');
        $this->addSql('CREATE INDEX IDX_2E01276937CD6DD0 ON knifes (mechanism_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes DROP FOREIGN KEY FK_2E01276937CD6DD0');
        $this->addSql('DROP INDEX IDX_2E01276937CD6DD0 ON knifes');
        $this->addSql('ALTER TABLE knifes DROP mechanism_id');
    }
}
