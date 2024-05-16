<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516152117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bases ADD department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bases ADD CONSTRAINT FK_217B2A3BAE80F5DF FOREIGN KEY (department_id) REFERENCES "departments" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_217B2A3BAE80F5DF ON bases (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "bases" DROP CONSTRAINT FK_217B2A3BAE80F5DF');
        $this->addSql('DROP INDEX IDX_217B2A3BAE80F5DF');
        $this->addSql('ALTER TABLE "bases" DROP department_id');
    }
}
