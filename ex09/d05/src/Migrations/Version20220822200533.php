<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822200533 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses ADD addresses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA75165713BC80 FOREIGN KEY (addresses_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_6FCA75165713BC80 ON addresses (addresses_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA75165713BC80');
        $this->addSql('DROP INDEX IDX_6FCA75165713BC80 ON addresses');
        $this->addSql('ALTER TABLE addresses DROP addresses_id');
    }
}
