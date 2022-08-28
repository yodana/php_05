<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828220539 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee CHANGE hours hours enum(\'8\', \'6\', \'4\'), CHANGE position position enum(\'manager\', \'account_manager\', \'qa_manager\', \'dev_manager\', \'ceo\', \'coo\', \'backend_dev\', \'frontend_dev\', \'qa_tester\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA75165713BC80');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A11C8FB41');
        $this->addSql('ALTER TABLE employee CHANGE hours hours VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE position position VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104ae7927c74 TO UNIQ_5A8A6C8DE7927C74');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104af85e0677 TO UNIQ_5A8A6C8DF85E0677');
    }
}
