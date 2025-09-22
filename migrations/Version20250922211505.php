<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250922211505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TYPE recurrence_type_enum AS ENUM(\'daily\', \'weekly\', \'monthly\')');
        $this->addSql('ALTER TABLE recurrence_rules ALTER recurrence_type TYPE recurrence_type_enum USING recurrence_type::recurrence_type_enum');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recurrence_rules ALTER recurrence_type TYPE VARCHAR(255)');
        $this->addSql('DROP TYPE recurrence_type_enum');
    }
}
