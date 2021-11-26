<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationOutboundEvent;

class TestApplicationOutboundErrorEvent extends ApplicationOutboundEvent
{


    public function __construct(array $data)
    {
        parent::__construct("TEST_EVENT_ERROR", $data);
    }
}