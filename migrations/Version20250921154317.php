<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250921154317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recurrence_rules (id SERIAL NOT NULL, recurrence_type VARCHAR(255) NOT NULL, interval INT NOT NULL, days_of_week INT NOT NULL, days_of_month INT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tags (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FBC94265E237E06 ON tags (name)');
        $this->addSql('CREATE TABLE tasks (id SERIAL NOT NULL, recurrence_rules_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deadline TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, starts_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50586597EA3F9393 ON tasks (recurrence_rules_id)');
        $this->addSql('CREATE TABLE task_tag (task_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(task_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_6C0B4F048DB60186 ON task_tag (task_id)');
        $this->addSql('CREATE INDEX IDX_6C0B4F04BAD26311 ON task_tag (tag_id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597EA3F9393 FOREIGN KEY (recurrence_rules_id) REFERENCES recurrence_rules (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F048DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F04BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_50586597EA3F9393');
        $this->addSql('ALTER TABLE task_tag DROP CONSTRAINT FK_6C0B4F048DB60186');
        $this->addSql('ALTER TABLE task_tag DROP CONSTRAINT FK_6C0B4F04BAD26311');
        $this->addSql('DROP TABLE recurrence_rules');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE task_tag');
    }
}
