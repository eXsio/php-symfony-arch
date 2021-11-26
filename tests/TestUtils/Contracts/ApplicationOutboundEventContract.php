<?php

namespace App\Tests\TestUtils\Contracts;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use PHPUnit\Framework\TestCase;

abstract class ApplicationOutboundEventContract extends TestCase
{

    use ApplicationEventContractLoader;

    protected function verifyContracts(ApplicationOutboundEvent $event, array $contracts): bool
    {
        $invalidContracts = [];
        foreach ($contracts as $contract) {
            $msg = $this->verifyContract($event, $contract);
            if ($msg !== null) {
                $invalidContracts[$contract] = $msg;
            }
        }
        if (count($invalidContracts) > 0) {
            $this->fail(sprintf("The '%s' Output Event doesn't fulfill the following Contracts: %s", $event::class, json_encode($invalidContracts)));
        }
        return true;
    }

    private function verifyContract(ApplicationOutboundEvent $event, string $contract): ?string
    {
        $eventData = $event->getData();
        $contractData = $this->readContract($contract);
        $missingKeys = [];
        foreach (array_keys($contractData) as $key) {
            if (!isset($eventData[$key])) {
                array_push($missingKeys, $key);
            }
        }
        return count($missingKeys) == 0 ? null : implode(", ", $missingKeys);
    }

}