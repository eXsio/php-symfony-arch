<?php

namespace App\Infrastructure\Events;

use Symfony\Component\Uid\Ulid;

/**
 * Event ID is a maintenance utility that allows for tracking Event Chains across possibly distributed Environments.
 * If an Event is Published as a result of handling a Parent Event, its ID will be populated with the Parent's Event ID.
 */
class EventIdHolder
{
    private static ?Ulid $eventId = null;

    public static function set(Ulid $eventId): void
    {
        EventIdHolder::$eventId = $eventId;
    }

    public static function clear(): void
    {
        EventIdHolder::$eventId = null;
    }

    public static function isSet(): bool
    {
        return EventIdHolder::$eventId != null;
    }

    public static function get(): ?Ulid
    {
        return EventIdHolder::$eventId;
    }
}