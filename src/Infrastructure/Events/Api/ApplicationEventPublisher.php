<?php

namespace App\Infrastructure\Events\Api;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Infrastructure\Events\EventIdHolder;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Implementation of the Event Publisher based on Symfony Messenger
 */
class ApplicationEventPublisher implements ApplicationEventPublisherInterface
{

    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface     $logger
    )
    {
    }

    public function publish(ApplicationOutboundEvent $event)
    {
        if (EventIdHolder::isSet()) {
            $event->setEventId(EventIdHolder::get());
        }
        $this->logger->info(sprintf("[event]: Publishing Outbound Event %s(%s)", $event::class, $event));
        $this->messageBus->dispatch($event);
    }

}