<?php

namespace App\Tests\Infrastructure\Events;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

class ApplicationInboundEventSpec extends TestCase
{
    /**
     * @param mixed $val
     * @param string $methodName
     * @param bool $nullable
     * @param mixed $expectedVal
     *
     * @dataProvider getInboundData
     */
    public function testApplicationInboundEvent(mixed $val, string $methodName, bool $nullable, mixed $expectedVal)
    {
        //given:
        $event = new TestApplicationInboundEvent(["val" => $val, "_eventId" => new Ulid()]);


        //when:
        $resultVal = null;
        $exeption = null;
        try {
            $resultVal = $event->$methodName("val", $nullable);
        } catch (\Exception $e) {
            $exeption = $e;
        }

        //then:
        if (!$nullable && $resultVal == null) {
            self::assertNotNull($exeption);
        } else {
            self::assertEquals($expectedVal, $resultVal);
        }

    }

    public function getInboundData(): array
    {
        $dt = new \DateTime();
        $ulid = new Ulid();
        $dtStr = $dt->format("Y-m-d H:i:s");
        $dt = \DateTime::createFromFormat("Y-m-d H:i:s", $dtStr);
        return [
            ['val', 'string', false, 'val'],
            [null, 'string', false, 'val'],
            [null, 'string', true, null],

            [1, 'int', false, 1],
            ['1', 'int', false, 1],
            [null, 'int', false, 1],
            [null, 'int', true, null],

            [$ulid, 'ulid', false, $ulid],
            [(string)$ulid, 'ulid', false, $ulid],
            [null, 'ulid', false, $ulid],
            [null, 'ulid', true, null],

            [$dt, 'dateTime', false, $dt],
            [$dtStr, 'dateTime', false, $dt],
            [null, 'dateTime', false, $dt],
            [null, 'dateTime', true, null],

            [['val'], 'array', false, ['val']],
            [null, 'array', false, ['val']],
            [null, 'array', true, null],
        ];
    }
}