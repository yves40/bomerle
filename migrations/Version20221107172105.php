<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107172105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knifes_handle (knifes_id INT NOT NULL, handle_id INT NOT NULL, INDEX IDX_11B90A5F8C85B742 (knifes_id), INDEX IDX_11B90A5F9C256C9C (handle_id), PRIMARY KEY(knifes_id, handle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knifes_handle ADD CONSTRAINT FK_11B90A5F8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE knifes_handle ADD CONSTRAINT FK_11B90A5F9C256C9C FOREIGN KEY (handle_id) REFERENCES handle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes_handle DROP FOREIGN KEY FK_11B90A5F8C85B742');
        $this->addSql('ALTER TABLE knifes_handle DROP FOREIGN KEY FK_11B90A5F9C256C9C');
        $this->addSql('DROP TABLE knifes_handle');
    }
}
