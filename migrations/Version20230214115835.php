<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214115835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON consultation');
        $this->addSql('ALTER TABLE consultation CHANGE reference id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX `primary` ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance ADD idconsultation_id VARCHAR(255) NOT NULL, CHANGE reference id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C83E08C56 FOREIGN KEY (idconsultation_id) REFERENCES consultation (id)');
        $this->addSql('CREATE INDEX IDX_924B326C83E08C56 ON ordonnance (idconsultation_id)');
        $this->addSql('ALTER TABLE ordonnance ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation MODIFY id VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON consultation');
        $this->addSql('ALTER TABLE consultation CHANGE id reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE consultation ADD PRIMARY KEY (reference)');
        $this->addSql('ALTER TABLE ordonnance MODIFY id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C83E08C56');
        $this->addSql('DROP INDEX IDX_924B326C83E08C56 ON ordonnance');
        $this->addSql('DROP INDEX `PRIMARY` ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance ADD reference VARCHAR(255) NOT NULL, DROP id, DROP idconsultation_id');
        $this->addSql('ALTER TABLE ordonnance ADD PRIMARY KEY (reference)');
    }
}
