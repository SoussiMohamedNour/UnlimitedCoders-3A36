<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215171048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ordonnance_medicament (ordonnance_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_FE7DFAEE2BF23B8F (ordonnance_id), INDEX IDX_FE7DFAEEAB0D61F7 (medicament_id), PRIMARY KEY(ordonnance_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEE2BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEEAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance ADD consultation_id INT NOT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('CREATE INDEX IDX_924B326C62FF6CDF ON ordonnance (consultation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEE2BF23B8F');
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEEAB0D61F7');
        $this->addSql('DROP TABLE ordonnance_medicament');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C62FF6CDF');
        $this->addSql('DROP INDEX IDX_924B326C62FF6CDF ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance DROP consultation_id');
    }
}
