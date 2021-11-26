<?php

namespace App\Tests\TestUtils;


use App\Infrastructure\Security\LoggedInUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Ulid;

abstract class IntegrationTest extends WebTestCase
{
    const DEFAULT_USER_ID = 'user@exsio.com';
    const DEFAULT_USER_PASSWORD = 'user';

    private ?KernelBrowser $client = null;

    /**
     * @before
     */
    public function setupClient()
    {
        if ($this->client == null) {
            $this->client = $this->createAuthenticatedClient();
        }
    }

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
                $this->createToken())
        );
        return $client;
    }

    public function getClient(): KernelBrowser
    {
        if (!isset($this->client) || $this->client == null) {
            throw new \RuntimeException("invalid Client");
        }
        return $this->client;
    }

    private function createToken(): string
    {
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        $user = new LoggedInUser(new Ulid(), self::DEFAULT_USER_ID, ['ROLE_USER']);
        $token = new TestBrowserToken(['ROLE_USER'], $user);
        $tokenStorage->setToken($token);
        $jwtManager = $this->getContainer()->get(JWTTokenManagerInterface::class);
        return $jwtManager->create($user);
    }

}