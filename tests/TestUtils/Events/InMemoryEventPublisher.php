<?php

namespace App\Tests\TestUtils\Events;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Events\ApplicationOutboundEvent;
use DusanKasan\Knapsack\Collection;
use PHPUnit\Framework\Assert;

class InMemoryEventPublisher implements ApplicationEventPublisherInterface
{
    /**
     * @var Collection<ApplicationOutboundEvent>
     */
    private static Collection $publishedEvents;

    public function __construct()
    {
        InMemoryEventPublisher::clear();
    }


    public function publish(ApplicationOutboundEvent $event)
    {
        InMemoryEventPublisher::$publishedEvents = InMemoryEventPublisher::$publishedEvents->append($event);
    }

    public static function clear()
    {
        InMemoryEventPublisher::$publishedEvents = Collection::from([]);
    }

    /**
     * @param string $eventClass
     * @return array<ApplicationOutboundEvent>
     */
    public static function get(string $eventClass): array
    {
        $result = [];
        foreach (InMemoryEventPublisher::$publishedEvents
                     ->filter(function ($event) use ($eventClass) {
                         return $event::class == $eventClass;
                     })
                     ->toArray() as $event) {
            array_push($result, $event);
        }
        return $result;
    }

    public static function assertEventData(array $expectedData, ApplicationOutboundEvent $event)
    {
        $eventData = $event->getData();
        foreach ($expectedData as $key => $value) {
            Assert::assertTrue(isset($eventData[$key]), "Missing Event Data Key: $key");
            if (self::isNotNullCheck($key)) {
                Assert::assertNotNull($eventData[$key], "Null Event Data Key: $key");
            } else {
                Assert::assertEquals($expectedData[$key], $eventData[$key], "Invalid Event Data Key: $key");
            }

        }
    }

    private static function isNotNullCheck(string $key): bool
    {
        return str_ends_with($key, 'Id') || str_ends_with($key, 'At');
    }


}