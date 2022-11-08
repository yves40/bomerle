<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107173508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knifes_metals (knifes_id INT NOT NULL, metals_id INT NOT NULL, INDEX IDX_794FFD6D8C85B742 (knifes_id), INDEX IDX_794FFD6DB4E97D59 (metals_id), PRIMARY KEY(knifes_id, metals_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knifes_metals ADD CONSTRAINT FK_794FFD6D8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE knifes_metals ADD CONSTRAINT FK_794FFD6DB4E97D59 FOREIGN KEY (metals_id) REFERENCES metals (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes_metals DROP FOREIGN KEY FK_794FFD6D8C85B742');
        $this->addSql('ALTER TABLE knifes_metals DROP FOREIGN KEY FK_794FFD6DB4E97D59');
        $this->addSql('DROP TABLE knifes_metals');
    }
}
