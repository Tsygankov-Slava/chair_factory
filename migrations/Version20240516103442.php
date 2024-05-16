<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516103442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE basic_chairs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE chair_base_materials_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE chair_upholstery_materials_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "bases_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "materials_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "bases" (id INT NOT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, code INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, base_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "materials" (id INT NOT NULL, category_id INT NOT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_order (id INT NOT NULL, order_id INT NOT NULL, base JSON NOT NULL, material TEXT NOT NULL, price NUMERIC(5, 0) NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN product_order.material IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE basic_chairs');
        $this->addSql('DROP TABLE chair_upholstery_materials');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE chair_base_materials');
        $this->addSql('ALTER TABLE orders ADD status_type INT NOT NULL');
        $this->addSql('ALTER TABLE orders DROP status');
        $this->addSql('ALTER TABLE orders DROP basic_chair_id_array');
        $this->addSql('ALTER TABLE orders DROP chair_base_material_id_array');
        $this->addSql('ALTER TABLE orders DROP chair_upholstery_material_array');
        $this->addSql('ALTER TABLE orders DROP chairs_quantity_array');
        $this->addSql('ALTER TABLE orders RENAME COLUMN price TO total_price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "bases_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "materials_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE product_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE basic_chairs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE chair_base_materials_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE chair_upholstery_materials_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE basic_chairs (id INT NOT NULL, type VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chair_upholstery_materials (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chair_base_materials (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE "bases"');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE "materials"');
        $this->addSql('DROP TABLE product_order');
        $this->addSql('DROP TABLE status');
        $this->addSql('ALTER TABLE "orders" ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "orders" ADD basic_chair_id_array JSON NOT NULL');
        $this->addSql('ALTER TABLE "orders" ADD chair_base_material_id_array JSON NOT NULL');
        $this->addSql('ALTER TABLE "orders" ADD chair_upholstery_material_array JSON NOT NULL');
        $this->addSql('ALTER TABLE "orders" ADD chairs_quantity_array JSON NOT NULL');
        $this->addSql('ALTER TABLE "orders" DROP status_type');
        $this->addSql('ALTER TABLE "orders" RENAME COLUMN total_price TO price');
    }
}
