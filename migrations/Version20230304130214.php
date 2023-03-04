<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304130214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, article_desc VARCHAR(5000) NOT NULL, article_date DATE NOT NULL, file VARCHAR(10000) DEFAULT NULL, nbcomment INT DEFAULT NULL, nblike INT DEFAULT NULL, nbdislike INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, commentairecontenu VARCHAR(255) NOT NULL, commentaireauth VARCHAR(255) NOT NULL, commentairedate DATE NOT NULL, INDEX IDX_67F068BC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_utilisateur_omar (commentaire_id INT NOT NULL, utilisateur_omar_id INT NOT NULL, INDEX IDX_5D5C264ABA9CD190 (commentaire_id), INDEX IDX_5D5C264AD29722AC (utilisateur_omar_id), PRIMARY KEY(commentaire_id, utilisateur_omar_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, matriculemedecin VARCHAR(255) NOT NULL, idpatient VARCHAR(255) NOT NULL, dateconsultation DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, dosage INT NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, consultation_id INT NOT NULL, validite INT NOT NULL, INDEX IDX_924B326C62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance_medicament (ordonnance_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_FE7DFAEE2BF23B8F (ordonnance_id), INDEX IDX_FE7DFAEEAB0D61F7 (medicament_id), PRIMARY KEY(ordonnance_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT NOT NULL, sexe VARCHAR(255) NOT NULL, num_tel VARCHAR(8) NOT NULL, cin VARCHAR(8) NOT NULL, is_verified TINYINT(1) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, isbanned TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_omar (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_omar_article (utilisateur_omar_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_20817EB5D29722AC (utilisateur_omar_id), INDEX IDX_20817EB57294869C (article_id), PRIMARY KEY(utilisateur_omar_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire_utilisateur_omar ADD CONSTRAINT FK_5D5C264ABA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_utilisateur_omar ADD CONSTRAINT FK_5D5C264AD29722AC FOREIGN KEY (utilisateur_omar_id) REFERENCES utilisateur_omar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEE2BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEEAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur_omar_article ADD CONSTRAINT FK_20817EB5D29722AC FOREIGN KEY (utilisateur_omar_id) REFERENCES utilisateur_omar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_omar_article ADD CONSTRAINT FK_20817EB57294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7294869C');
        $this->addSql('ALTER TABLE commentaire_utilisateur_omar DROP FOREIGN KEY FK_5D5C264ABA9CD190');
        $this->addSql('ALTER TABLE commentaire_utilisateur_omar DROP FOREIGN KEY FK_5D5C264AD29722AC');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C62FF6CDF');
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEE2BF23B8F');
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEEAB0D61F7');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE utilisateur_omar_article DROP FOREIGN KEY FK_20817EB5D29722AC');
        $this->addSql('ALTER TABLE utilisateur_omar_article DROP FOREIGN KEY FK_20817EB57294869C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE commentaire_utilisateur_omar');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE ordonnance_medicament');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_omar');
        $this->addSql('DROP TABLE utilisateur_omar_article');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
