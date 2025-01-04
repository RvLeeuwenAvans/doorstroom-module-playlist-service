<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103215724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist_user (playlist_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2D8AE12B6BBD148 (playlist_id), INDEX IDX_2D8AE12BA76ED395 (user_id), PRIMARY KEY(playlist_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist_user ADD CONSTRAINT FK_2D8AE12B6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_user ADD CONSTRAINT FK_2D8AE12BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_user DROP FOREIGN KEY FK_2D8AE12B6BBD148');
        $this->addSql('ALTER TABLE playlist_user DROP FOREIGN KEY FK_2D8AE12BA76ED395');
        $this->addSql('DROP TABLE playlist_user');
    }
}
