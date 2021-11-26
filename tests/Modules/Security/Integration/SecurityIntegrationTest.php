<?php

namespace App\Tests\Modules\Security\Integration;

use App\Tests\TestUtils\IntegrationTest;
use DusanKasan\Knapsack\Collection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class SecurityIntegrationTest extends IntegrationTest
{

    protected ?string $token = null;

    /**
     * Create a client with a default Authorization header.
     *
     *
     * @return KernelBrowser
     */
    protected function createAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s',
                $this->createToken($client))
        );
        return $client;
    }

    private function createToken(KernelBrowser $client): string
    {
        $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                'username' => self::DEFAULT_USER_ID,
                'password' => self::DEFAULT_USER_PASSWORD,
            ))
        );
        $cookies = $client->getResponse()->headers->getCookies();
        $bearer = Collection::from($cookies)
            ->find(function ($c) {
                return $c->getName() == "BEARER";
            });
        if ($bearer == null) {
            $this->fail("Unable to Authenticate in Test");
        }
        $this->token = $bearer->getValue();
        return $this->token;
    }
}