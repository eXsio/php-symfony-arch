<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Modules\Posts\Api\Command\BaselinePostsCommand;
use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostBaselinedOEvent;
use App\Modules\Posts\Domain\Event\Outbound\PostDeletedOEvent;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;

trait PostsBaseliner
{


    public function __construct(
        private PostsFindingRepositoryInterface    $findingRepository,
        private ApplicationEventPublisherInterface $eventPublisher
    )
    {
    }

    public function baseline(BaselinePostsCommand $command): void
    {
        $this->baselineExistentPosts($command);
        $this->baselineDeletedPosts($command);

    }

    /**
     * @param BaselinePostsCommand $command
     */
    protected function baselineDeletedPosts(BaselinePostsCommand $command): void
    {
        $deletedPostIds = $this->findingRepository->findDeletedPostIdsForBaseline($command->getFrom());
        foreach ($deletedPostIds as $postId) {
            $this->eventPublisher->publish(new PostDeletedOEvent(new DeleteExistingPostDto($postId)));
        }
    }

    /**
     * @param BaselinePostsCommand $command
     */
    protected function baselineExistentPosts(BaselinePostsCommand $command): void
    {
        $posts = $this->findingRepository->findExistingPostsForBaseline($command->getFrom());
        foreach ($posts as $post) {
            $this->eventPublisher->publish(new PostBaselinedOEvent($post));
        }
    }
}