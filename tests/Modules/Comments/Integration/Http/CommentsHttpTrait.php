<?php

namespace App\Tests\Modules\Comments\Integration\Http;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\Command\Response\CreateCommentCommandResponse;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Api\Query\FindLatestCommentsQuery;
use App\Modules\Comments\Api\Query\Response\FindCommentsByPostIdQueryResponse;
use App\Tests\TestUtils\SerializationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait CommentsHttpTrait
{
    use SerializationTrait;

    public function createComment(CreateCommentCommand $command): CreateCommentCommandResponse
    {
        $client = $this->getClient();
        $client->request('POST', '/api/comments/', [], [], [], $this->json($command));
        return $this->responseObject($client, CreateCommentCommandResponse::class);
    }

    /**
     * @param int $oageNo
     * @return Page
     */
    public function findLatestComments(FindLatestCommentsQuery $query): Page
    {
        $client = $this->getClient();
        $client->request('GET', '/api/comments/?pageNo=' . $query->getPageNo());
        return $this->responseObject($client, Page::class);
    }

    /**
     * @param FindCommentsByPostIdQuery $query
     * @return array<FindCommentsByPostIdQueryResponse>
     */
    public function findCommentsForPost(FindCommentsByPostIdQuery $query): array
    {
        $client = $this->getClient();
        $client->request('GET', '/api/comments/' . $query->getPostId());
        return $this->responseObjects($client, FindCommentsByPostIdQueryResponse::class);
    }

    /**
     * @return KernelBrowser
     */
    public abstract function getClient(): KernelBrowser;
}