<?php

namespace App\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This Listener will work for REST Calls from every Module
 * and will automatically renew the Tokens that are about to be expired.
 * This should prevent the logging-out of an active user without UI having to explicitly renew the Token.
 */
class JWTCommonListener
{
    use JWTBaseListener;

    const REFRESH_TIME = 1800;
    private ?array $payload = null;
    private ?UserInterface $user = null;

    public function __construct(
        private JWTTokenManagerInterface $jwtManager)
    {
    }

    public function onAuthenticatedAccess(JWTAuthenticatedEvent $event)
    {
        $this->payload = $event->getPayload();
        $this->user = $event->getToken()->getUser();
    }

    public function onAuthenticatedResponse(ResponseEvent $event)
    {
        if ($this->payload != null && $this->user != null) {
            $expireTime = $this->payload['exp'] - time();
            if ($expireTime < static::REFRESH_TIME) {
                // Refresh token
                $jwt = $this->jwtManager->create($this->user);
                $response = $event->getResponse();
                // Set cookie
                $this->createCookie($response, $jwt);
            }
        }
    }
}