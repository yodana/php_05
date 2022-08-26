<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826195146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD superiors INT DEFAULT NULL, CHANGE hours hours enum(\'8\', \'6\', \'4\'), CHANGE position position enum(\'manager\', \'account_manager\', \'qa_manager\', \'dev_manager\', \'ceo\', \'coo\', \'backend_dev\', \'frontend_dev\', \'qa_tester\')');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A12D748641 FOREIGN KEY (superiors) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A12D748641 ON employee (superiors)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A12D748641');
        $this->addSql('DROP INDEX IDX_5D9F75A12D748641 ON employee');
        $this->addSql('ALTER TABLE employee DROP superiors, CHANGE hours hours VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, CHANGE position position VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`');
    }
}
