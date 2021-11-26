<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Modules\Posts\Domain\Dto\PostDto;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;

class PostsValidator
{
    /**
     * @param PostsFindingRepositoryInterface $findingRepository
     */
    public function __construct(
        private PostsFindingRepositoryInterface $findingRepository
    )
    {
    }

    /**
     * @param CreatePostCommand $command
     */
   public function preCreate(CreatePostCommand $command): void
    {
        $this->validatePostBody($command->getBody());
        $this->validatePostTitle($command->getTitle());
        $this->validatePostTags($command->getTags());
    }

    /**
     * @param UpdatePostCommand $command
     * @return PostDto|null
     */
    public function preUpdate(UpdatePostCommand $command): ?PostDto
    {
        $post = $this->validatePostExists($command->getId());
        $this->validatePostBody($command->getBody());
        $this->validatePostTitle($command->getTitle());
        $this->validatePostTags($command->getTags());
        return $post;
    }

    /**
     * @param DeletePostCommand $command
     * @return PostDto|null
     */
    public function preDelete(DeletePostCommand $command): ?PostDto
    {
        return $this->validatePostExists($command->getId());
    }

    public function preHandleCommentCreated(CommentCreatedPostsIEvent $event): void
    {
        $this->validatePostExists($event->getPostId());
    }

    public function preHandleCommentsBaselined(CommentsBaselinedPostsIEvent $event): void
    {
        $this->validatePostExists($event->getPostId());
    }

    /**
     * @param Ulid $id
     * @return PostDto|null
     */
    private function validatePostExists(Ulid $id): ?PostDto
    {
        $post = $this->findingRepository->findPost($id);
        if ($post == null) {
            throw new NotFoundHttpException();
        }
        return $post;
    }

    /**
     * @param string $body
     */
    private function validatePostBody(string $body): void
    {
        if (trim($body) == '') {
            throw new BadRequestHttpException("Post Body cannot be empty");
        }
    }

    /**
     * @param string $title
     */
    private function validatePostTitle(string $title): void
    {
        if (trim($title) == '') {
            throw new BadRequestHttpException("Post Title cannot be empty");
        }
    }

    /**
     * @param array<string> $tags
     */
    private function validatePostTags(array $tags): void
    {
        if (count(Collection::from($tags)->filter(function ($tag) {
                return trim($tag) == '';
            })->toArray()) > 0) {
            throw new BadRequestHttpException("Post Tags cannot be blank");
        }
    }
}