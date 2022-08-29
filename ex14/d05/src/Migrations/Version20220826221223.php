<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826221223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, superiors INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthdate DATETIME NOT NULL, active TINYINT(1) NOT NULL, employee_since DATETIME NOT NULL, employee_until DATETIME NOT NULL, salary INT NOT NULL, hours enum(\'8\', \'6\', \'4\'), position enum(\'manager\', \'account_manager\', \'qa_manager\', \'dev_manager\', \'ceo\', \'coo\', \'backend_dev\', \'frontend_dev\', \'qa_tester\'), INDEX IDX_5D9F75A12D748641 (superiors), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A12D748641 FOREIGN KEY (superiors) REFERENCES employee (id)');
    }
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A12D748641');
        $this->addSql('DROP TABLE employee');
        $this->addSql('ALTER TABLE address ADD a_name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, DROP name');
        $this->addSql('ALTER TABLE address_e12 CHANGE a_name name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA75165713BC80');
        $this->addSql('ALTER TABLE addresses ADD a_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, DROP name');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A11C8FB41');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104ae7927c74 TO UNIQ_5A8A6C8DE7927C74');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104af85e0677 TO UNIQ_5A8A6C8DF85E0677');
    }
}
