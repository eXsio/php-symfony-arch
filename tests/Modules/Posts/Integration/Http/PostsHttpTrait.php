<?php

namespace App\Tests\Modules\Posts\Integration\Http;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Command\Response\CreatePostCommandResponse;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Api\Query\Response\FindPostQueryResponse;
use App\Tests\TestUtils\SerializationTrait;

trait PostsHttpTrait
{
    use SerializationTrait;

    public function createPost(CreatePostCommand $command): CreatePostCommandResponse
    {
        $client = $this->getClient();
        $client->request('POST', '/api/admin/posts/', [], [], [], $this->json($command));
        return $this->responseObject($client, CreatePostCommandResponse::class);
    }

    public function updatePost(UpdatePostCommand $command): void
    {
        $this->getClient()->request('PUT', '/api/admin/posts/' . $command->getId(), [], [], [], $this->json($command));
    }

    public function deletePost(DeletePostCommand $command): void
    {
        $this->getClient()->request('DELETE', '/api/admin/posts/' . $command->getId());
    }

    public function findAllPosts(FindAllPostsQuery $query): Page
    {
        $client = $this->getClient();
        $client->request('GET', '/api/posts/?pageNo=' . $query->getPageNo());
        return $this->responseObject($client, Page::class);
    }

    public function findPostById(FindPostByIdQuery $query): FindPostQueryResponse
    {
        $client = $this->getClient();
        $client->request('GET', '/api/posts/' . $query->getId());
        return $this->responseObject($client, FindPostQueryResponse::class);
    }
}