<?php

namespace App\Infrastructure\Events\Api;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Infrastructure\Events\EventIdHolder;
use DusanKasan\Knapsack\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * This is a base for all Services that want to listen to the Events published by other Applications/Modules.
 * It's based on Symfony Messenger and uses the subscribe() method to match the incoming Events to the Handlers.
 */
abstract class ApplicationEventSubscriber implements MessageHandlerInterface
{

    private Collection $subscribedHandlers;

    public function __construct(
        private LoggerInterface $logger
    )
    {
        $this->subscribedHandlers = Collection::from($this->subscribe());
    }


    /**
     * @param ApplicationOutboundEvent $event
     */
    public function __invoke(ApplicationOutboundEvent $event)
    {
        EventIdHolder::set($event->getEventId());
        $this->logger->info(sprintf("[event]: Received Outbound Event %s(%s)", $event::class, $event));
        foreach ($this->matchHandlers($event) as $handler) {
            try {
                $this->invokeHandler($handler, $event);
            } catch (\Throwable $e) {
                $this->logger->error(sprintf("[event]: Exception caught when handling an Outbound Event '%s(%s)':%s",
                        $event::class, json_encode($event->getData()), $e->getMessage())
                );
                $this->logger->error($e->getTraceAsString());
            }

        }
        EventIdHolder::clear();
    }

    /**
     * @param ApplicationOutboundEvent $event
     * @return array<EventHandlerReference>
     */
    private function matchHandlers(ApplicationOutboundEvent $event): array
    {
        return $this
            ->subscribedHandlers
            ->filter(function ($value, $key) use ($event) {
                return $key == $event->getName();
            })->map(function ($value) {
                return $value;
            })->toArray();
    }

    /**
     * @return array<string, EventHandlerReference>
     */
    protected abstract function subscribe(): array;

    /**
     * @param EventHandlerReference $handler
     * @param ApplicationOutboundEvent $event
     * @throws \ReflectionException
     */
    public function invokeHandler(EventHandlerReference $handler, ApplicationOutboundEvent $event): void
    {
        $handlerMethod = new \ReflectionMethod($this, $handler->getHandlerMethodName());
        $className = $handler->getInboundEventClass();
        $inboundEvent = new $className($event->getData());
        $this->logger->info(sprintf("[event]: Calling Event Subscriber %s:%s for Inbound Event %s(%s)",
            $this::class, $handlerMethod->name, $event::class, $event
        ));
        $handlerMethod->getClosure($this)($inboundEvent);
    }
}