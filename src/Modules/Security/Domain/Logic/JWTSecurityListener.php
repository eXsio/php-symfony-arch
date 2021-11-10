<?php

namespace App\Modules\Security\Domain\Logic;

use App\Infrastructure\Security\JWTBaseListener;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This Listener:
 * - enriches the JWT Token payload with User ID
 * - stores the JWT Token in a httpOnly Cookie
 * - sends the User Details as a Response for Login Action
 */
trait JWTSecurityListener
{
    use JWTBaseListener;

    /**
     * @param LoggedInUserProviderInterface $loggedInUserProvider
     */
    public function __construct(
        private LoggedInUserProviderInterface $loggedInUserProvider
    )
    {
    }

    /**
     * @param JWTCreatedEvent $event
     */
    function onJwtCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $user = $this->loggedInUserProvider->getUser();
        $payload['id'] = $user->getId();
        $event->setData($payload);
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $eventData = $event->getData();
        if (isset($eventData['token'])) {
            $response = $event->getResponse();
            $jwt = $eventData['token'];
            $this->createCookie($response, $jwt);
            $user = $this->loggedInUserProvider->getUser();
            $event->setData([
                    "id" => $user->getId(),
                    "login" => $user->getEmail(),
                    "roles" => $user->getRoles()
                ]
            );
        } else {
            throw new HttpException(500, 'JWT Token not found');
        }
    }
}