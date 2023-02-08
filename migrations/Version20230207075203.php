<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207075203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, matriculemedecin VARCHAR(255) NOT NULL, idpatient VARCHAR(255) NOT NULL, dateconsultation DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicaments (id INT AUTO_INCREMENT NOT NULL, ordonnance_id INT NOT NULL, nom VARCHAR(255) NOT NULL, dosage INT NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_DD988ACB2BF23B8F (ordonnance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, consultation_id INT NOT NULL, validite INT NOT NULL, INDEX IDX_924B326C62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medicaments ADD CONSTRAINT FK_DD988ACB2BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medicaments DROP FOREIGN KEY FK_DD988ACB2BF23B8F');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C62FF6CDF');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE medicaments');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
