<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219101505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE remboursement ADD depot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EF8510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EF8510D4DE ON remboursement (depot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EF8510D4DE');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EF8510D4DE ON remboursement');
        $this->addSql('ALTER TABLE remboursement DROP depot_id');
    }
}
