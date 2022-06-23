<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615095210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cart_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cart_product (id INT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2890CCAA9D86650F ON cart_product (user_id)');
        $this->addSql('CREATE INDEX IDX_2890CCAADE18E50B ON cart_product (product_id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA9D86650F FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAADE18E50B FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ALTER user_id TYPE INT');
        $this->addSql('ALTER TABLE "order" ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" ALTER user_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cart_product_id_seq CASCADE');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('ALTER TABLE "order" ALTER user_id TYPE INT');
        $this->addSql('ALTER TABLE "order" ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" ALTER user_id DROP NOT NULL');
    }
}
