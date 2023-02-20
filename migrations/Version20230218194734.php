<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218194734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche_medicament (fiche_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_34CA76CDDF522508 (fiche_id), INDEX IDX_34CA76CDAB0D61F7 (medicament_id), PRIMARY KEY(fiche_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_medicament ADD CONSTRAINT FK_34CA76CDDF522508 FOREIGN KEY (fiche_id) REFERENCES fiche (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_medicament ADD CONSTRAINT FK_34CA76CDAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche ADD depot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiche ADD CONSTRAINT FK_4C13CC788510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C13CC788510D4DE ON fiche (depot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_medicament DROP FOREIGN KEY FK_34CA76CDDF522508');
        $this->addSql('ALTER TABLE fiche_medicament DROP FOREIGN KEY FK_34CA76CDAB0D61F7');
        $this->addSql('DROP TABLE fiche_medicament');
        $this->addSql('ALTER TABLE fiche DROP FOREIGN KEY FK_4C13CC788510D4DE');
        $this->addSql('DROP INDEX UNIQ_4C13CC788510D4DE ON fiche');
        $this->addSql('ALTER TABLE fiche DROP depot_id');
    }
}
