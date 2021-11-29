<?php

namespace App\Tests\Infrastructure\Events\Api;

use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Tests\Infrastructure\Events\TestApplicationInboundErrorEvent;
use App\Tests\Infrastructure\Events\TestApplicationInboundEvent;

class TestEventSubscriber extends ApplicationEventSubscriber
{

    private ?TestApplicationInboundEvent $event = null;

    public function subscribe(): array
    {
        return [
            TestApplicationInboundEvent::class => 'handleEvent',
            TestApplicationInboundErrorEvent::class => 'handleEventError',
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