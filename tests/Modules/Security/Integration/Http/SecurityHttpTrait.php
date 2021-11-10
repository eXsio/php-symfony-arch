<?php

namespace App\Tests\Modules\Security\Integration\Http;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Api\Query\FindPostsByUserIdQuery;
use App\Modules\Security\Api\Query\Response\FindPostsByUserIdQueryResponse;
use App\Tests\TestUtils\SerializationTrait;

trait SecurityHttpTrait
{

    use SerializationTrait;

    /**
     * @param FindPostsByUserIdQuery $query
     * @return Page<FindPostsByUserIdQueryResponse>
     */
    public function findPostsByUserId(FindPostsByUserIdQuery $query): Page
    {
        $client = $this->getClient();
        $client->request('GET', '/api/security/' . $query->getUserId());
        return $this->responseObject($client, Page::class);
    }
}