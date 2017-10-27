<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171021113751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, school_class_id INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, first_name VARCHAR(255) NOT NULL, insertion VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(35) NOT NULL COMMENT \'(DC2Type:phone_number)\', dtype VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), INDEX IDX_8D93D64914463F54 (school_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, slb_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2ED7EC55E237E06 (name), UNIQUE INDEX UNIQ_2ED7EC56899672A (slb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64914463F54 FOREIGN KEY (school_class_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC56899672A FOREIGN KEY (slb_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC56899672A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64914463F54');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE classes');
    }
}
