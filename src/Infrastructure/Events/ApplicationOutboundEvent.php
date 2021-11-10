<?php

namespace App\Infrastructure\Events;

use Symfony\Component\Uid\Ulid;

/**
 * This is a base class for all Application Outbound Events.
 * An Outbound Event is an Event that should be published via the Event Publisher to all interested, possibly remote Applications/Modules.
 *
 * In the Context of this Demo, each Outbound Event is paired with zero or more Inbound Events.
 * The pairing is based on an Event Name (string).
 */
abstract class ApplicationOutboundEvent
{

    private Ulid $_eventId;

    public function __construct(
        private string $name,
        private array  $data,
    )
    {
        $this->_eventId = new Ulid();
        $this->data['_eventId'] = $this->_eventId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Ulid
     */
    public function getEventId(): Ulid
    {
        return $this->_eventId;
    }

    /**
     * @param Ulid $eventId
     */
    public function setEventId(Ulid $eventId): void
    {
        $this->_eventId = $eventId;
    }

    public function __toString(): string
    {
        return json_encode($this->data);
    }


}