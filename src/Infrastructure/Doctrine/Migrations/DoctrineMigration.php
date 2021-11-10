<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * This is the base class for all App Migrations.
 * It ensures that the Migrations are executed against the proper database and skipped for all others.
 */
abstract class DoctrineMigration extends AbstractMigration
{
    public function preUp(Schema $schema): void
    {
        $parameters = $this->connection->getParams();
        $this->skipIf($parameters['dbname'] != $this->getDbName(),
            "This is the other DB\'s migration, pass a correct --em parameter");
    }

    public function preDown(Schema $schema): void
    {
        $parameters = $this->connection->getParams();
        $this->skipIf($parameters['dbname'] != $this->getDbName(),
            "This is the other DB\'s migration, pass a correct --em parameter");
    }

    protected abstract function getDbName(): string;
}