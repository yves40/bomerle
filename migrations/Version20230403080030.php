<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403080030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date_range (id INT AUTO_INCREMENT NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide_images (id INT AUTO_INCREMENT NOT NULL, slide_show_id INT NOT NULL, filename VARCHAR(64) NOT NULL, rank SMALLINT NOT NULL, INDEX IDX_42F946CE1D87D6D (slide_show_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide_show (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, active TINYINT(1) NOT NULL, datein DATE DEFAULT NULL, dateout DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slide_images ADD CONSTRAINT FK_42F946CE1D87D6D FOREIGN KEY (slide_show_id) REFERENCES slide_show (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slide_images DROP FOREIGN KEY FK_42F946CE1D87D6D');
        $this->addSql('DROP TABLE date_range');
        $this->addSql('DROP TABLE slide_images');
        $this->addSql('DROP TABLE slide_show');
    }
}
