<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825202029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, addresses_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D4E6F815713BC80 (addresses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, addresses_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6FCA75165713BC80 (addresses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, bank_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_53A23E0A11C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account_e11 (id INT AUTO_INCREMENT NOT NULL, bank_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_44AE387011C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F815713BC80 FOREIGN KEY (addresses_id) REFERENCES person_e11 (id)');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA75165713BC80 FOREIGN KEY (addresses_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A11C8FB41 FOREIGN KEY (bank_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE bank_account_e11 ADD CONSTRAINT FK_44AE387011C8FB41 FOREIGN KEY (bank_id) REFERENCES person_e11 (id)');
        $this->addSql('DROP TABLE person_e10');
        $this->addSql('ALTER TABLE person_e11 ADD marital_statut TINYINT(1) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE enable enable TINYINT(1) NOT NULL, CHANGE birthdate birthdate DATETIME NOT NULL');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX username TO UNIQ_A593104AF85E0677');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX email TO UNIQ_A593104AE7927C74');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_e10 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE bank_account_e11');
        $this->addSql('ALTER TABLE person_e11 DROP marital_statut, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE enable enable TINYINT(1) DEFAULT NULL, CHANGE birthdate birthdate DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104ae7927c74 TO email');
        $this->addSql('ALTER TABLE person_e11 RENAME INDEX uniq_a593104af85e0677 TO username');
    }
}
