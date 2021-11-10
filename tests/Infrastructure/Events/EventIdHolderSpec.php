<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\EventIdHolder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

class EventIdHolderSpec extends TestCase
{

    /**
     * @test
     */
    public function testEventIdHolder()
    {
        self::assertFalse(EventIdHolder::isSet());
        self::assertNull(EventIdHolder::get());

        $id = new Ulid();
        EventIdHolder::set($id);

        self::assertTrue(EventIdHolder::isSet());
        self::assertEquals($id, EventIdHolder::get());

        EventIdHolder::clear();

        self::assertFalse(EventIdHolder::isSet());
        self::assertNull(EventIdHolder::get());
    }
}