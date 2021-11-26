<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationInboundEvent;

class TestApplicationInboundErrorEvent extends ApplicationInboundEvent
{
    public function __construct(array $data)
    {
        parent::__construct("TEST_EVENT_ERROR", $data);
    }

}