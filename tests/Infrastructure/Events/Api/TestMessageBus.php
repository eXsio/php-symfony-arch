<?php

namespace App\Tests\Infrastructure\Events\Api;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class TestMessageBus implements MessageBusInterface
{


    public function __construct(
        private TestEventSubscriber $subscriber
    )
    {
    }

    public function dispatch(object $message, array $stamps = []): Envelope
    {
//        $events = $this->subscriber->subscribe();
//        $methodName = $events['TEST_EVENT']->getHandlerMethodName();
//        $inboundEventCclass = $events['TEST_EVENT']->getInboundEventClass();
        $this->subscriber->__invoke($message);
        return new Envelope($message, $stamps);
    }
}