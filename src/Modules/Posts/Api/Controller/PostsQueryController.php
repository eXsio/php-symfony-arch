<?php

namespace App\Modules\Posts\Api\Controller;

use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

#[Route('/api/posts')]
class PostsQueryController extends AbstractController
{

    /**
     * @param PostsApiInterface $postsApi
     */
    public function __construct(
        private PostsApiInterface $postsApi
    )
    {
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/', methods: ['GET'])]
    public function getAllPosts(Request $request): Response
    {
        $pageNo = $request->query->get("pageNo", "1");
        return $this->json(
            $this->postsApi->findAllPosts(new FindAllPostsQuery(intval($pageNo)))
        );
    }

    /**
     * @param string $id
     * @return Response
     */
    #[Route('/{id}', methods: ['GET'])]
    public function getPostById(string $id): Response
    {
        $response = $this->postsApi->findPostById(new FindPostByIdQuery(new Ulid($id)));
        if ($response == null) {
            throw new NotFoundHttpException();
        }
        return $this->json($response);
    }
}