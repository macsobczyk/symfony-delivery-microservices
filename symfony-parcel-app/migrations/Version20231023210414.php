<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231023210414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, receiver_id INT NOT NULL, status INT NOT NULL, package_id VARCHAR(255) DEFAULT NULL, session_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_DE686795CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcel (id INT AUTO_INCREMENT NOT NULL, package_id INT NOT NULL, width INT NOT NULL, length INT NOT NULL, height INT NOT NULL, waybill VARCHAR(50) DEFAULT NULL, INDEX IDX_C99B5D60F44CABFF (package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiver (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, post_code VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, email_address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id)');
        $this->addSql('ALTER TABLE parcel ADD CONSTRAINT FK_C99B5D60F44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE686795CD53EDB6');
        $this->addSql('ALTER TABLE parcel DROP FOREIGN KEY FK_C99B5D60F44CABFF');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('DROP TABLE receiver');
    }
}
