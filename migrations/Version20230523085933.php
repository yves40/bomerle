<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523085933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slide_images RENAME INDEX idx_42f946ce1d87d6d TO IDX_42F946CE8B14E343');
        $this->addSql('ALTER TABLE slide_show ADD gallery TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slide_images RENAME INDEX idx_42f946ce8b14e343 TO IDX_42F946CE1D87D6D');
        $this->addSql('ALTER TABLE slide_show DROP gallery');
    }
}
