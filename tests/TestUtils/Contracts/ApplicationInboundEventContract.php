<?php

namespace App\Tests\TestUtils\Contracts;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

abstract class ApplicationInboundEventContract extends TestCase
{

    use ApplicationEventContractLoader;

    protected function verifyContract(string $eventClass, string $contract): bool
    {
        $data = $this->getInboundEvent($contract);

        try {
            new $eventClass($data);
        } catch (\Exception $e) {
            $this->fail(sprintf("Event Class %s cannot be constructed with the Contract Data '%s': %s", $eventClass, $contract, $e->getMessage()));
        }
        return true;
    }


}