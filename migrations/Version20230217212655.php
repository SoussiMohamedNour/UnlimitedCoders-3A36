<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217212655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32B9D6493');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A2CEF1DE9');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B33D865311');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F3D865311');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF64F31A84');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE planning');
        $this->addSql('ALTER TABLE calendrier ADD utilisateur_id INT DEFAULT NULL, ADD date DATE NOT NULL, ADD heure_debut TIME NOT NULL, ADD heure_fin TIME NOT NULL, DROP horaire_dispo, DROP horaire_dispo_end_time');
        $this->addSql('ALTER TABLE calendrier ADD CONSTRAINT FK_B2753CB9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_B2753CB9FB88E14F ON calendrier (utilisateur_id)');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AFB88E14F');
        $this->addSql('DROP INDEX IDX_65E8AA0AFB88E14F ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A2CEF1DE9 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP utilisateur_id, DROP id_calendrier, CHANGE date date DATE NOT NULL, CHANGE heure heure TIME NOT NULL, CHANGE planning_rdv_id calendrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AFF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AFF52FC51 ON rendez_vous (calendrier_id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3FF52FC51');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3FF52FC51 ON utilisateur');
        $this->addSql('DROP INDEX UNIQ_1D1C63B33D865311 ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B32B9D6493 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD rendez_vous_id INT DEFAULT NULL, DROP calendrier_id, DROP planning_id, DROP disponibilite_id, DROP desponibilite_id, CHANGE date_naiss date_naiss DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B391EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B391EF7EAA ON utilisateur (rendez_vous_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, planning_id INT DEFAULT NULL, date DATE NOT NULL, heure_debut DATETIME NOT NULL, heure_fin DATETIME NOT NULL, INDEX IDX_2CBACE2F3D865311 (planning_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_D499BFF64F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F3D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF64F31A84 FOREIGN KEY (medecin_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE calendrier DROP FOREIGN KEY FK_B2753CB9FB88E14F');
        $this->addSql('DROP INDEX IDX_B2753CB9FB88E14F ON calendrier');
        $this->addSql('ALTER TABLE calendrier ADD horaire_dispo VARCHAR(255) NOT NULL, ADD horaire_dispo_end_time VARCHAR(255) NOT NULL, DROP utilisateur_id, DROP date, DROP heure_debut, DROP heure_fin');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AFF52FC51');
        $this->addSql('DROP INDEX IDX_65E8AA0AFF52FC51 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD utilisateur_id INT NOT NULL, ADD id_calendrier VARCHAR(255) NOT NULL, CHANGE date date VARCHAR(255) NOT NULL, CHANGE heure heure VARCHAR(255) NOT NULL, CHANGE calendrier_id planning_rdv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A2CEF1DE9 FOREIGN KEY (planning_rdv_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AFB88E14F ON rendez_vous (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A2CEF1DE9 ON rendez_vous (planning_rdv_id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B391EF7EAA');
        $this->addSql('DROP INDEX IDX_1D1C63B391EF7EAA ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD planning_id INT DEFAULT NULL, ADD disponibilite_id INT DEFAULT NULL, ADD desponibilite_id INT DEFAULT NULL, CHANGE date_naiss date_naiss DATE NOT NULL, CHANGE rendez_vous_id calendrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B33D865311 FOREIGN KEY (planning_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3FF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendrier (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32B9D6493 FOREIGN KEY (disponibilite_id) REFERENCES disponibilite (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3FF52FC51 ON utilisateur (calendrier_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B33D865311 ON utilisateur (planning_id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B32B9D6493 ON utilisateur (disponibilite_id)');
    }
}
