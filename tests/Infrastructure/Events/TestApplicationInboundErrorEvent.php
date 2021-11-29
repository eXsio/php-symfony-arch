<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationInboundEvent;

class TestApplicationInboundErrorEvent extends ApplicationInboundEvent
{
    public static function getName(): string
    {
        return "TEST_EVENT_ERROR";
    }
}