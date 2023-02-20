<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210124052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, date DATE NOT NULL, etat VARCHAR(255) NOT NULL, id_fiche INT NOT NULL, id_ordonnance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE dépot_dossier');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE plafond ADD etat VARCHAR(255) NOT NULL, CHANGE montant_maximal montant_max DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE remboursement ADD reference VARCHAR(255) NOT NULL, ADD reponse VARCHAR(255) NOT NULL, DROP référence_remboursement, DROP réponse, CHANGE montant_remboursement montant DOUBLE PRECISION NOT NULL, CHANGE date_remboursement date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, pseudonom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, motdepasse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dépot_dossier (id INT AUTO_INCREMENT NOT NULL, référence_dossier VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_dépot DATE NOT NULL, état VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, id_fiche INT NOT NULL, id_ordonnance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, pseudonom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE depot');
        $this->addSql('ALTER TABLE plafond DROP etat, CHANGE montant_max montant_maximal DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE remboursement ADD référence_remboursement VARCHAR(255) NOT NULL, ADD réponse VARCHAR(255) NOT NULL, DROP reference, DROP reponse, CHANGE montant montant_remboursement DOUBLE PRECISION NOT NULL, CHANGE date date_remboursement DATE NOT NULL');
    }
}
