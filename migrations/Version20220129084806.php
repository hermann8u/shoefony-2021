<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129084806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sto_color_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sto_color (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sto_product_color (product_id INT NOT NULL, color_id INT NOT NULL, PRIMARY KEY(product_id, color_id))');
        $this->addSql('CREATE INDEX IDX_5AC117F64584665A ON sto_product_color (product_id)');
        $this->addSql('CREATE INDEX IDX_5AC117F67ADA1FB5 ON sto_product_color (color_id)');
        $this->addSql('ALTER TABLE sto_product_color ADD CONSTRAINT FK_5AC117F64584665A FOREIGN KEY (product_id) REFERENCES sto_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sto_product_color ADD CONSTRAINT FK_5AC117F67ADA1FB5 FOREIGN KEY (color_id) REFERENCES sto_color (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sto_product_color DROP CONSTRAINT FK_5AC117F67ADA1FB5');
        $this->addSql('DROP SEQUENCE sto_color_id_seq CASCADE');
        $this->addSql('DROP TABLE sto_color');
        $this->addSql('DROP TABLE sto_product_color');
    }
}
