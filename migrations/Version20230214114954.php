<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214114954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON consultation');
        $this->addSql('ALTER TABLE consultation DROP id');
        $this->addSql('ALTER TABLE consultation ADD PRIMARY KEY (reference)');
        $this->addSql('ALTER TABLE ordonnance MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance DROP id');
        $this->addSql('ALTER TABLE ordonnance ADD PRIMARY KEY (reference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ordonnance ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
