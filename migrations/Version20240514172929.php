<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514172929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE messenger_messages_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "basic_chairs_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "chair_base_materials_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "chair_upholstery_materials_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "orders_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "roles_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "basic_chairs" (id INT NOT NULL, type VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "chair_base_materials" (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "chair_upholstery_materials" (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "orders" (id INT NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, basic_chair_id_array JSON NOT NULL, chair_base_material_id_array JSON NOT NULL, chair_upholstery_material_array JSON NOT NULL, chairs_quantity_array JSON NOT NULL, price NUMERIC(5, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "roles" (id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE basic_chair');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE chair_upholstery_material');
        $this->addSql('DROP TABLE chair_base_material');
        $this->addSql('DROP TABLE messenger_messages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "basic_chairs_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "chair_base_materials_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "chair_upholstery_materials_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "orders_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "roles_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE messenger_messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, basic_chair_id_array TEXT NOT NULL, chair_base_material_id_array TEXT NOT NULL, chair_upholstery_material_array TEXT NOT NULL, chairs_quantity_array TEXT NOT NULL, price NUMERIC(5, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "order".basic_chair_id_array IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN "order".chair_base_material_id_array IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN "order".chair_upholstery_material_array IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN "order".chairs_quantity_array IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE basic_chair (id INT NOT NULL, type VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chair_upholstery_material (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chair_base_material (id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_75ea56e0fb7336f0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX idx_75ea56e0e3bd61ce ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_75ea56e016ba31db ON messenger_messages (delivered_at)');
        $this->addSql('DROP TABLE "basic_chairs"');
        $this->addSql('DROP TABLE "chair_base_materials"');
        $this->addSql('DROP TABLE "chair_upholstery_materials"');
        $this->addSql('DROP TABLE "orders"');
        $this->addSql('DROP TABLE "roles"');
        $this->addSql('DROP TABLE "users"');
    }
}
