<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationInboundEvent;
use App\Infrastructure\Events\ApplicationOutboundEvent;
use Symfony\Component\Uid\Ulid;

class TestApplicationOutboundEvent extends ApplicationOutboundEvent
{


    public function __construct(array $data)
    {
        parent::__construct("TEST_EVENT", $data);
    }
}