<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103154239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F871179CD6');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NAME ON genre');
        $this->addSql('ALTER TABLE genre ADD name VARCHAR(255) NOT NULL, DROP name_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NAME ON genre (name)');
        $this->addSql('ALTER TABLE song ADD genre_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1C2428192 FOREIGN KEY (genre_id_id) REFERENCES song (id)');
        $this->addSql('CREATE INDEX IDX_33EDEEA1C2428192 ON song (genre_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA1C2428192');
        $this->addSql('DROP INDEX IDX_33EDEEA1C2428192 ON song');
        $this->addSql('ALTER TABLE song DROP genre_id_id');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NAME ON genre');
        $this->addSql('ALTER TABLE genre ADD name_id INT DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F871179CD6 FOREIGN KEY (name_id) REFERENCES song (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NAME ON genre (name_id)');
    }
}
