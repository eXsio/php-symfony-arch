<?php

namespace App\Tests\Infrastructure\Events\Api;

use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Infrastructure\Events\Api\EventHandlerReference;
use App\Tests\Infrastructure\Events\TestApplicationInboundErrorEvent;
use App\Tests\Infrastructure\Events\TestApplicationInboundEvent;

class TestEventSubscriber extends ApplicationEventSubscriber
{

    private ?TestApplicationInboundEvent $event = null;

    public function subscribe(): array
    {
        return [
            EventHandlerReference::create('handleEvent', TestApplicationInboundEvent::class),
            EventHandlerReference::create('handleEventError', TestApplicationInboundErrorEvent::class)
        ];
    }

    public function handleEvent(TestApplicationInboundEvent $event)
    {
        $this->event = $event;
    }

    public function handleEventError()
    {
        throw new \RuntimeException("error");
    }

    /**
     * @return TestApplicationInboundEvent|null
     */
    public function getEvent(): ?TestApplicationInboundEvent
    {
        return $this->event;
    }


}