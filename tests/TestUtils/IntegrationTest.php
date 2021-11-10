<?php

namespace App\Tests\TestUtils;


use App\Infrastructure\Security\LoggedInUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Security;
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
     * @param string $username
     * @param string $password
     *
     * @return KernelBrowser
     */
    private function createAuthenticatedClient($username = self::DEFAULT_USER_ID, $password = self::DEFAULT_USER_PASSWORD): KernelBrowser
    {

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->createToken($client, $username, $password)));
        return $client;
    }

    public function getClient(): KernelBrowser
    {
        if (!isset($this->client) || $this->client == null) {
            throw new \RuntimeException("invalid Client");
        }
        return $this->client;
    }

    protected function createToken(KernelBrowser $client, $username, $password): string
    {
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        $user = new LoggedInUser(new Ulid(), $username, ['ROLE_USER']);
        $token = new TestBrowserToken(['ROLE_USER'], $user);
        $tokenStorage->setToken($token);
        $jwtManager = $this->getContainer()->get(JWTTokenManagerInterface::class);
        return $jwtManager->create($user);
    }

}