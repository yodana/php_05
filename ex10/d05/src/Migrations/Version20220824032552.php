<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824032552 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY addresses_id');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY bank_id');
        $this->addSql('DROP TABLE person_e02');
        $this->addSql('DROP TABLE person_e04');
        $this->addSql('DROP TABLE person_e06');
        $this->addSql('DROP TABLE person_e10');
        $this->addSql('DROP TABLE persons');
        $this->addSql('DROP INDEX addresses_id ON addresses');
        $this->addSql('ALTER TABLE addresses ADD id INT AUTO_INCREMENT NOT NULL, ADD addresses_id INT DEFAULT NULL, DROP person_id, CHANGE name name VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA75165713BC80 FOREIGN KEY (addresses_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_6FCA75165713BC80 ON addresses (addresses_id)');
        $this->addSql('DROP INDEX person_id ON bank_account');
        $this->addSql('ALTER TABLE bank_account ADD id INT AUTO_INCREMENT NOT NULL, ADD bank_id INT DEFAULT NULL, DROP account_id, DROP person_id, CHANGE amount amount INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A11C8FB41 FOREIGN KEY (bank_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_53A23E0A11C8FB41 ON bank_account (bank_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_e02 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, enable TINYINT(1) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, address LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE person_e04 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, enable TINYINT(1) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, address LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE person_e06 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, enable TINYINT(1) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, address LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE person_e10 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE persons (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, enable TINYINT(1) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, marital_status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, UNIQUE INDEX email (email), UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE addresses MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA75165713BC80');
        $this->addSql('DROP INDEX IDX_6FCA75165713BC80 ON addresses');
        $this->addSql('ALTER TABLE addresses DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE addresses ADD person_id INT NOT NULL, DROP id, DROP addresses_id, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT addresses_id FOREIGN KEY (person_id) REFERENCES persons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX addresses_id ON addresses (person_id)');
        $this->addSql('ALTER TABLE bank_account MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A11C8FB41');
        $this->addSql('DROP INDEX IDX_53A23E0A11C8FB41 ON bank_account');
        $this->addSql('ALTER TABLE bank_account DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bank_account ADD account_id INT NOT NULL, ADD person_id INT NOT NULL, DROP id, DROP bank_id, CHANGE amount amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT bank_id FOREIGN KEY (person_id) REFERENCES persons (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX person_id ON bank_account (person_id)');
        $this->addSql('ALTER TABLE bank_account ADD PRIMARY KEY (account_id)');
    }
}
