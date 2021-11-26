<?php

namespace App\Tests\Modules\Comments\Unit;

use App\Modules\Comments\Api\CommentsApiInterface;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Query\FindCommentsPostHeadersQuery;
use App\Modules\Comments\Domain\CommentsApi;
use App\Tests\Modules\Comments\Unit\Repository\InMemoryCommentsRepository;
use App\Tests\Modules\Comments\Unit\Transaction\InMemoryCommentsTransactionFactory;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Ulid;

abstract class CommentsSpec extends TestCase
{
    use ApplicationEventContractLoader;

    protected CommentsApiInterface $commentsApi;

    /**
     * @before
     */
    protected function setupCommentsApi()
    {
        $repository = new InMemoryCommentsRepository();
        $publisher = new InMemoryEventPublisher();
        $this->commentsApi = new CommentsApi(
            new InMemoryCommentsTransactionFactory(),
            $repository,
            $repository,
            $repository,
            $repository,
            $repository,
            $repository,
            $publisher,
            $this->createMock(LoggerInterface::class)
        );
    }

    protected function createPost(): Ulid
    {
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));
        $this->commentsApi->onPostCreated($event);
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        return $headers[0]->getId();
    }
}