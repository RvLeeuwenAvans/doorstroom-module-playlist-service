<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103162831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA1C2428192');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1C2428192 FOREIGN KEY (genre_id_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA1C2428192');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1C2428192 FOREIGN KEY (genre_id_id) REFERENCES song (id)');
    }
}
