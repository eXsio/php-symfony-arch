<?php

namespace App\Modules\Tags\Api\Controller;

use App\Modules\Tags\Api\Query\FindPostsByTagQuery;
use App\Modules\Tags\Api\Query\FindTagsQuery;
use App\Modules\Tags\Api\TagsApiInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tags')]
class TagsQueryController extends AbstractController
{

    /**
     * @param TagsApiInterface $tagsApi
     */
    public function __construct(
        private TagsApiInterface $tagsApi
    )
    {
    }

    /**
     * @return Response
     */
    #[Route("/", methods: ['GET'])]
    public function findTags(): Response
    {
        return $this->json(
            $this->tagsApi->findTags(new FindTagsQuery())
        );
    }

    /**
     * @param string $tag
     * @return Response
     */
    #[Route("/{tag}", methods: ['GET'])]
    public function findPostsByTag(string $tag, Request $request): Response
    {
        $pageNo = $request->query->get("pageNo", "1");
        return $this->json(
            $this->tagsApi->findPostsByTag(new FindPostsByTagQuery($tag, intval($pageNo)))
        );
    }
}