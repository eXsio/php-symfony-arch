<?php

namespace App\Tests\Infrastructure\Events;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class TestApplicationInboundEvent extends ApplicationInboundEvent
{
    public function __construct(array $data)
    {
        parent::__construct("TEST_EVENT", $data);
    }

    public function string(string $fieldName, bool $nullable = false): ?string
    {
        return parent::string($fieldName, $nullable);
    }

    public function int(string $fieldName, bool $nullable = false): ?int
    {
        return parent::int($fieldName, $nullable); // TODO: Change the autogenerated stub
    }

    public function array(string $fieldName, bool $nullable = false): ?array
    {
        return parent::array($fieldName, $nullable); // TODO: Change the autogenerated stub
    }

    public function ulid(string $fieldName, bool $nullable = false): ?Ulid
    {
        return parent::ulid($fieldName, $nullable); // TODO: Change the autogenerated stub
    }

    public function dateTime(string $fieldName, bool $nullable = false): ?\DateTime
    {
        return parent::dateTime($fieldName, $nullable); // TODO: Change the autogenerated stub
    }
}