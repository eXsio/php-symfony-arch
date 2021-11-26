<?php

namespace App\Modules\Security\Api\Controller;

use App\Modules\Security\Api\Query\FindPostsByUserIdQuery;
use App\Modules\Security\Api\SecurityApiInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

#[Route('/api/security')]
class SecurityQueryController extends AbstractController
{

    /**
     * @param SecurityApiInterface $securityApi
     */
    public function __construct(
        private SecurityApiInterface $securityApi
    )
    {
    }

    /**
     * @param string $userId
     * @return Response
     */
    #[Route('/{userId}', methods: ['GET'])]
   public function getPostsByUserId(string $userId, Request $request): Response
    {
        $pageNo = $request->query->get("pageNo", "1");
        return $this->json(
            $this->securityApi->findPostsByUserId(new FindPostsByUserIdQuery(new Ulid($userId), intval($pageNo)))
        );
    }
}