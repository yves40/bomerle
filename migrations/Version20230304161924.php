<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304161924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_roles (users_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_51498A8E67B3B43D (users_id), INDEX IDX_51498A8E38C751C4 (roles_id), PRIMARY KEY(users_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8E38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users DROP role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8E67B3B43D');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8E38C751C4');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('ALTER TABLE users ADD role JSON NOT NULL');
    }
}
