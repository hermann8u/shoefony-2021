<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129072150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sto_brand_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sto_brand (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE sto_product ADD sto_brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE sto_product ADD CONSTRAINT FK_B21FD430CA2A6A21 FOREIGN KEY (sto_brand_id) REFERENCES sto_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B21FD430CA2A6A21 ON sto_product (sto_brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sto_product DROP CONSTRAINT FK_B21FD430CA2A6A21');
        $this->addSql('DROP SEQUENCE sto_brand_id_seq CASCADE');
        $this->addSql('DROP TABLE sto_brand');
        $this->addSql('DROP INDEX IDX_B21FD430CA2A6A21');
        $this->addSql('ALTER TABLE sto_product DROP sto_brand_id');
    }
}
