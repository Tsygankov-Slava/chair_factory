<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516140315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('new_table');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);

        // Add a new column to an existing table
        $table = $schema->getTable('existing_table');
        $table->addColumn('new_column', 'string', ['length' => 255]);
    }

    public function down(Schema $schema): void
    {
        // Drop the new table
        $schema->dropTable('new_table');

        // Remove the new column from the existing table
        $table = $schema->getTable('existing_table');
        $table->dropColumn('new_column');
    }
}
