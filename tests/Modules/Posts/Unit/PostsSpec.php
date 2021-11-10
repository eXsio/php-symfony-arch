<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Domain\PostsApi;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use App\Tests\TestUtils\Security\InMemoryLoggedInUserProvider;
use App\Tests\Modules\Posts\Unit\Repository\InMemoryPostsRepository;
use App\Tests\Modules\Posts\Unit\Transaction\InMemoryPostTransactionFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

abstract class PostsSpec extends TestCase
{
    protected PostsApiInterface $postsApi;

    /**
     * @before
     */
    protected function setupPostsApi()
    {
        $repository = new InMemoryPostsRepository();
        $this->postsApi = new PostsApi(
            new InMemoryEventPublisher(),
            $repository,
            $repository,
            $repository,
            $repository,
            new InMemoryLoggedInUserProvider(),
            new InMemoryPostTransactionFactory(),
            $repository,
            $repository,
            $this->createMock(LoggerInterface::class)
        );
    }
}