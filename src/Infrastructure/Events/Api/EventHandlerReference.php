<?php

namespace App\Infrastructure\Events\Api;

/**
 * Utility class that contains the name of the method that should be called upon an Inbound Event arrival,
 * and the class of appropriate Inbound Event.
 */
class EventHandlerReference
{

    private function __construct(
        private string $handlerMethodName,
        private string $inboundEventClass
    )
    {
    }

    /**
     * @return string
     */
    public function getHandlerMethodName(): string
    {
        return $this->handlerMethodName;
    }

    /**
     * @return string
     */
    public function getInboundEventClass(): string
    {
        return $this->inboundEventClass;
    }

    public static function create(string $handlerMethodName, string $inboundEventClass): EventHandlerReference
    {
        return new EventHandlerReference($handlerMethodName, $inboundEventClass);
    }


}