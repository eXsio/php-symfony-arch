<?php

namespace App\Tests\Modules\Tags\Unit;

use App\Modules\Tags\Api\TagsApiInterface;
use App\Modules\Tags\Domain\TagsApi;
use App\Tests\Modules\Tags\Unit\Repository\InMemoryTagsRepository;
use App\Tests\Modules\Tags\Unit\Transaction\InMemoryTagsTransactionFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

abstract class TagsSpec extends TestCase
{
    protected TagsApiInterface $tagsApi;

    /**
     * @before
     */
    protected function setupTagsApi()
    {
        $repository = new InMemoryTagsRepository();
        $this->tagsApi = new TagsApi(
            new InMemoryTagsTransactionFactory(),
            $repository,
            $repository,
            $this->createMock(LoggerInterface::class),
            $repository,
            $repository,
            $repository,
            $repository,
            $repository,
        );
    }
}