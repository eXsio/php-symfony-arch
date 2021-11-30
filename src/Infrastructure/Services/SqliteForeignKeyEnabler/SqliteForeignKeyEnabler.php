<?php

namespace App\Infrastructure\Services\SqliteForeignKeyEnabler;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

/**
 * SQLite has FK constraints disabled by default for BC.
 * Enable FK constraints to make sure the app isn't making any funny business in the DB.
 */
class SqliteForeignKeyEnabler implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::postConnect];
    }

    /**
     * @param ConnectionEventArgs $args
     * @throws \Doctrine\DBAL\Exception
     */
    public function postConnect(ConnectionEventArgs $args): void
    {
        $args->getConnection()->executeStatement('PRAGMA foreign_keys = ON;');
    }
}