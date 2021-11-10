<?php

namespace App\Infrastructure\Events\Api;

use App\Infrastructure\Events\ApplicationOutboundEvent;

/**
 * Application Event Publisher is used to send Outbound Events to the System's Event Bus.
 * The Event Bus can be either sync or async. The behavior of the Publisher should not differ based on the Transport Type.
 * Publisher should never throw any Exceptions, regardless of Transport Type, only log them.
 */
interface ApplicationEventPublisherInterface
{
    public function publish(ApplicationOutboundEvent $event);
}