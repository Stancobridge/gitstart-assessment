<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710171330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(14, 4) NOT NULL, image_url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_ID ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ID ON user (id, email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_ID ON `user`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_ID ON `user` (id)');
    }
}
