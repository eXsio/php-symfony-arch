<?php

namespace App\Infrastructure\Security;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base JWT Listener that is capable of creating a JWT-Token Cookie
 */
trait JWTBaseListener
{
    protected function createCookie(Response $response, string $token)
    {
        $response->headers->setCookie(
            new Cookie(
                "BEARER",
                $token,
                new \DateTime("+1 day"),
                "/",
                null,
                false,
                true,
                false,
                'strict'
            )
        );
    }
}