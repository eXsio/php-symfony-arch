<?php

namespace App\Tests\Modules\Security\Integration;

use App\Tests\TestUtils\IntegrationTest;
use DusanKasan\Knapsack\Collection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class SecurityIntegrationTest extends IntegrationTest
{

    protected ?string $token = null;

    protected function createToken(KernelBrowser $client, $username, $password): string
    {
        $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                'username' => $username,
                'password' => $password,
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