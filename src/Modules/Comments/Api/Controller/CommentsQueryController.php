<?php

namespace App\Modules\Comments\Api\Controller;

use App\Modules\Comments\Api\CommentsApiInterface;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Api\Query\FindLatestCommentsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

#[Route('/api/comments')]
class CommentsQueryController extends AbstractController
{
    /**
     * @param CommentsApiInterface $commentsApi
     */
    public function __construct(
        private CommentsApiInterface $commentsApi
    )
    {
    }

    /**
     * @param string $postId
     * @return Response
     */
    #[Route('/{postId}', methods: ['GET'])]
    public function getCommentsForPost(string $postId): Response
    {
        return $this->json(
            $this->commentsApi->findCommentsForPost(new FindCommentsByPostIdQuery(new Ulid($postId)))
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/', methods: ['GET'])]
    public function getLatestComments(Request $request): Response
    {
        $pageNo = $request->query->get("pageNo", "1");
        return $this->json(
            $this->commentsApi->findLatestComments(new FindLatestCommentsQuery(intval($pageNo)))
        );
    }
}