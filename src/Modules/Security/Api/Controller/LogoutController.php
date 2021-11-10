<?php

namespace App\Modules\Security\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/logout')]
class LogoutController extends AbstractController
{
    #[Route('/', methods: ['POST'])]
    public function logout(): Response
    {
        $response = new Response('Successfully Logged Out', Response::HTTP_OK);
        $response->headers->clearCookie('BEARER');
        return $response;
    }
}