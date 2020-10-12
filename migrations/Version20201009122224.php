<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201009122224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E1F1F2A24 ON item (element_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1F1F2A24');
        $this->addSql('DROP INDEX IDX_1F1B251E1F1F2A24 ON item');
    }
}
