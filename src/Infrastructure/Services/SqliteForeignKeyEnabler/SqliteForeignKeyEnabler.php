<?php

namespace App\Infrastructure\Services\SqliteForeignKeyEnabler;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

class SqliteForeignKeyEnabler implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [Events::postConnect];
    }

    public function postConnect(ConnectionEventArgs $args): void
    {
        if (strtolower($args->getConnection()->getDatabasePlatform()->getName()) !== 'sqlite') {
            return;
        }
        $args->getConnection()->exec('PRAGMA foreign_keys = ON;');
    }
}