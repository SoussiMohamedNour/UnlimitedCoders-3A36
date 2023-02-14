<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214120708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicament ADD ordonnance_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE medicament ADD CONSTRAINT FK_9A9C723A2BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('CREATE INDEX IDX_9A9C723A2BF23B8F ON medicament (ordonnance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicament DROP FOREIGN KEY FK_9A9C723A2BF23B8F');
        $this->addSql('DROP INDEX IDX_9A9C723A2BF23B8F ON medicament');
        $this->addSql('ALTER TABLE medicament DROP ordonnance_id');
    }
}
