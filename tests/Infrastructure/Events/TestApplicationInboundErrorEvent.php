<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class TestApplicationInboundErrorEvent extends ApplicationInboundEvent
{
    public function __construct(array $data)
    {
        parent::__construct("TEST_EVENT_ERROR", $data);
    }

}