<?php

namespace App\Tests\Infrastructure\Utils\Unit;

use App\Infrastructure\Utils\StringUtil;
use PHPUnit\Framework\TestCase;

class StringUtilSpec extends TestCase
{

    /**
     * @test
     * @dataProvider summaryProvider
     */
    public function shouldCreateSummaryFromString(?string $subject, int $length, string $expected): void {
        self::assertSame($expected, StringUtil::getSummary($subject, $length));
    }

    public function summaryProvider(): array
    {
        return [
            ['post body', 10, 'post body'],
            ['long post body', 10, 'long post...'],
            ['', 10, ''],
            [null, 10, '']
        ];
    }

}