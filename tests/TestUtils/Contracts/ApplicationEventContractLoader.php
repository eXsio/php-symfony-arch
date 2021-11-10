<?php

namespace App\Tests\TestUtils\Contracts;

use Symfony\Component\Uid\Ulid;

trait ApplicationEventContractLoader
{
    /**
     * @param string $contractName
     * @return array<string, mixed>
     */
    protected function readContract(string $contractName): array
    {
        $contractPath = sprintf("%s/%s.json", $this->getContractsPath(), $contractName);
        if (!file_exists($contractPath)) {
            $this->fail(sprintf("Contract File doesn't exist: %s", $contractPath));
        }

        $string = file_get_contents($contractPath);
        $data = json_decode($string, true);
        return $data;
    }

    protected function getInboundEvent(string $contract): array
    {
        $data = $this->readContract($contract);
        $data['_eventId'] = new Ulid();
        return $data;
    }

    protected function getContractsPath(): string
    {
        return dirname(__FILE__) . '/../../../contracts';
    }
}