<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218195720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot ADD patient_id INT DEFAULT NULL, ADD assurance_id INT DEFAULT NULL, ADD remboursement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCB288C3E3 FOREIGN KEY (assurance_id) REFERENCES assurance (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCF61EE8B FOREIGN KEY (remboursement_id) REFERENCES remboursement (id)');
        $this->addSql('CREATE INDEX IDX_47948BBC6B899279 ON depot (patient_id)');
        $this->addSql('CREATE INDEX IDX_47948BBCB288C3E3 ON depot (assurance_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47948BBCF61EE8B ON depot (remboursement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBC6B899279');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCB288C3E3');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCF61EE8B');
        $this->addSql('DROP INDEX IDX_47948BBC6B899279 ON depot');
        $this->addSql('DROP INDEX IDX_47948BBCB288C3E3 ON depot');
        $this->addSql('DROP INDEX UNIQ_47948BBCF61EE8B ON depot');
        $this->addSql('ALTER TABLE depot DROP patient_id, DROP assurance_id, DROP remboursement_id');
    }
}
