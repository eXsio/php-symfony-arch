<?php

namespace App\Tests\Modules\Security\Integration\Http;

use App\Tests\TestUtils\SerializationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait SecurityHttpTrait
{

    use SerializationTrait;

    public function logout(): string
    {
        $client = $this->getClient();
        $client->request('POST', '/api/logout/');
        return $client->getResponse()->getContent();
    }

    /**
     * @return KernelBrowser
     */
    public abstract function getClient(): KernelBrowser;
}