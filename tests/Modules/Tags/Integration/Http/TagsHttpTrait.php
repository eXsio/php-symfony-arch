<?php

namespace App\Tests\Modules\Tags\Integration\Http;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Api\Query\FindPostsByTagQuery;
use App\Modules\Tags\Api\Query\Response\FindPostsByTagQueryResponse;
use App\Modules\Tags\Api\Query\Response\FindTagsQueryResponse;
use App\Tests\TestUtils\SerializationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait TagsHttpTrait
{

    use SerializationTrait;

    /**
     * @return array<FindTagsQueryResponse>
     */
    public function findTags(): array
    {
        $client = $this->getClient();
        $client->request('GET', '/api/tags/');
        return $this->responseObjects($client, FindTagsQueryResponse::class);
    }

    /**
     * @param FindPostsByTagQuery $query
     * @return Page<FindPostsByTagQueryResponse>
     */
    public function findPostByTag(FindPostsByTagQuery $query): Page
    {
        $client = $this->getClient();
        $client->request('GET', '/api/tags/' . $query->getTag());
        return $this->responseObject($client, Page::class);
    }

    /**
     * @return KernelBrowser
     */
    public abstract function getClient(): KernelBrowser;
}