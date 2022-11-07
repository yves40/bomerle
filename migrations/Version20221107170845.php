<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107170845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knifes_accessories (knifes_id INT NOT NULL, accessories_id INT NOT NULL, INDEX IDX_C01A6A7C8C85B742 (knifes_id), INDEX IDX_C01A6A7C35D022EB (accessories_id), PRIMARY KEY(knifes_id, accessories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knifes_accessories ADD CONSTRAINT FK_C01A6A7C8C85B742 FOREIGN KEY (knifes_id) REFERENCES knifes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE knifes_accessories ADD CONSTRAINT FK_C01A6A7C35D022EB FOREIGN KEY (accessories_id) REFERENCES accessories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knifes_accessories DROP FOREIGN KEY FK_C01A6A7C8C85B742');
        $this->addSql('ALTER TABLE knifes_accessories DROP FOREIGN KEY FK_C01A6A7C35D022EB');
        $this->addSql('DROP TABLE knifes_accessories');
    }
}
