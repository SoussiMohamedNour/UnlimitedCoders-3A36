<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224023805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assurance (id INT AUTO_INCREMENT NOT NULL, id_assurance INT NOT NULL, nom_assurance VARCHAR(255) NOT NULL, plafond DOUBLE PRECISION NOT NULL, adresse_assurance VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche (id INT AUTO_INCREMENT NOT NULL, id_fiche INT NOT NULL, date_fiche DATE NOT NULL, montant_consultation DOUBLE PRECISION NOT NULL, montant_medicaments DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_medicament (fiche_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_34CA76CDDF522508 (fiche_id), INDEX IDX_34CA76CDAB0D61F7 (medicament_id), PRIMARY KEY(fiche_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, libelle VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, id_patient INT NOT NULL, cin INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, mobile INT NOT NULL, adresse_patient VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remboursement (id INT AUTO_INCREMENT NOT NULL, depot_id INT DEFAULT NULL, id_remboursement INT NOT NULL, date_remboursement DATE NOT NULL, reponse VARCHAR(255) NOT NULL, montant_rembourse DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_C0C0D9EF8510D4DE (depot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_medicament ADD CONSTRAINT FK_34CA76CDDF522508 FOREIGN KEY (fiche_id) REFERENCES fiche (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_medicament ADD CONSTRAINT FK_34CA76CDAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EF8510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('ALTER TABLE depot ADD patient_id INT DEFAULT NULL, ADD assurance_id INT DEFAULT NULL, ADD fiche_id INT DEFAULT NULL, ADD id_dossier INT NOT NULL, ADD etat_dossier VARCHAR(255) NOT NULL, ADD regime VARCHAR(255) NOT NULL, ADD total_depense DOUBLE PRECISION NOT NULL, DROP reference, DROP etat, DROP id_fiche, DROP id_ordonnance, CHANGE date date_depot DATE NOT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCB288C3E3 FOREIGN KEY (assurance_id) REFERENCES assurance (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCDF522508 FOREIGN KEY (fiche_id) REFERENCES fiche (id)');
        $this->addSql('CREATE INDEX IDX_47948BBC6B899279 ON depot (patient_id)');
        $this->addSql('CREATE INDEX IDX_47948BBCB288C3E3 ON depot (assurance_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47948BBCDF522508 ON depot (fiche_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCB288C3E3');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCDF522508');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC6B899279');
        $this->addSql('ALTER TABLE fiche_medicament DROP FOREIGN KEY FK_34CA76CDDF522508');
        $this->addSql('ALTER TABLE fiche_medicament DROP FOREIGN KEY FK_34CA76CDAB0D61F7');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EF8510D4DE');
        $this->addSql('DROP TABLE assurance');
        $this->addSql('DROP TABLE fiche');
        $this->addSql('DROP TABLE fiche_medicament');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE remboursement');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX IDX_47948BBC6B899279 ON depot');
        $this->addSql('DROP INDEX IDX_47948BBCB288C3E3 ON depot');
        $this->addSql('DROP INDEX UNIQ_47948BBCDF522508 ON depot');
        $this->addSql('ALTER TABLE depot ADD reference VARCHAR(255) NOT NULL, ADD etat VARCHAR(255) NOT NULL, ADD id_ordonnance INT NOT NULL, DROP patient_id, DROP assurance_id, DROP fiche_id, DROP etat_dossier, DROP regime, DROP total_depense, CHANGE date_depot date DATE NOT NULL, CHANGE id_dossier id_fiche INT NOT NULL');
    }
}
