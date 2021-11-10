<?php

namespace App\Tests\Infrastructure\Events\Api;

use App\Infrastructure\Events\Api\ApplicationEventPublisher;
use App\Infrastructure\Events\EventIdHolder;
use App\Tests\Infrastructure\Events\TestApplicationOutboundErrorEvent;
use App\Tests\Infrastructure\Events\TestApplicationOutboundEvent;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Ulid;

class PublishSubscribeEventsSpec extends TestCase
{

    public function testPublishSubscribeEvents()
    {
        //given:
        EventIdHolder::set(new Ulid());
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new TestEventSubscriber($logger);
        $publisher = new ApplicationEventPublisher(
            new TestMessageBus($subscriber),
            $logger
        );

        //when:
        $publisher->publish(new TestApplicationOutboundEvent(["testKey" => "testVal"]));

        //then:
        $event = $subscriber->getEvent();
        self::assertNotNull($event);
        self::assertEquals("testVal", $event->string('testKey'));

    }

    public function testPublishSubscribeErrorEvents()
    {
        //given:
        EventIdHolder::set(new Ulid());
        $logger = $this->createMock(LoggerInterface::class);
        $subscriber = new TestEventSubscriber($logger);
        $publisher = new ApplicationEventPublisher(
            new TestMessageBus($subscriber),
            $logger
        );

        //when:
        $publisher->publish(new TestApplicationOutboundErrorEvent(["testKey" => "testVal"]));

        //then:
        $event = $subscriber->getEvent();
        self::assertNull($event);

    }
}