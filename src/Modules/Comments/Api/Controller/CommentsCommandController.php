<?php

namespace App\Modules\Comments\Api\Controller;

use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\CommentsApiInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments')]
class CommentsCommandController extends AbstractController
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
     * @param CreateCommentCommand $command
     * @return Response
     */
    #[Route('/', methods: ['POST'])]
    public function createComment(CreateCommentCommand $command): Response
    {
        return $this->json(
            $this->commentsApi->createComment($command)
        );
    }


}