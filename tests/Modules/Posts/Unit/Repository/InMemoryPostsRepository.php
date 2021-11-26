<?php

namespace App\Tests\Modules\Posts\Unit\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Dto\PostDto;
use App\Modules\Posts\Domain\Dto\PostHeaderDto;
use App\Modules\Posts\Domain\Dto\UpdatedPostsUserNameDto;
use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;
use App\Modules\Posts\Domain\Dto\UpdatePostCommentsDto;
use App\Modules\Posts\Domain\Repository\PostsCommentsEventHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsCreationRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsDeletionRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsUpdatingRepositoryInterface;
use DusanKasan\Knapsack\Collection;
use Symfony\Component\Uid\Ulid;

class InMemoryPostsRepository implements
    PostsCreationRepositoryInterface,
    PostsUpdatingRepositoryInterface,
    PostsDeletionRepositoryInterface,
    PostsFindingRepositoryInterface,
    PostsCommentsEventHandlingRepositoryInterface,
    PostsSecurityEventsHandlingRepositoryInterface
{

    private static Collection $posts;

    public function __construct()
    {
        self::clear();
    }

    public static function clear()
    {
        self::$posts = Collection::from([]);
    }


   public function createPost(CreateNewPostDto $newPost): Ulid
    {
        $id = new Ulid();
        self::$posts = self::$posts->append(
            new InMemoryPost(
                $id,
                $newPost->getTitle(),
                $newPost->getBody(),
                $newPost->getSummary(),
                $newPost->getTags(),
                [],
                $newPost->getCreatedById(),
                $newPost->getCreatedByName(),
                $newPost->getCreatedAt(),
                1
            )
        );
        return $id;
    }

   public function deletePost(DeleteExistingPostDto $dto): void
    {
        self::$posts = Collection::from(
            self::$posts
                ->filter(function ($post) use ($dto) {
                    return $post->getId() != $dto->getId();
                })
                ->toArray()
        );
    }

   public function updatePost(UpdateExistingPostDto $dto): void
    {
        foreach (self::$posts
                     ->filter(function ($post) use ($dto) {
                         return $post->getId() == $dto->getId();
                     })
                     ->toArray() as $post) {
            $post->setTitle($dto->getTitle());
            $post->setBody($dto->getBody());
            $post->setSummary($dto->getSummary());
            $post->setTags($dto->getTags());
            $post->setUpdatedAt(new \DateTime());
            $post->setVersion($post->getVersion() + 1);
        }
    }

    public function findPost(Ulid $id): ?PostDto
    {
        $found = self::$posts->find(function ($post) use ($id) {
            return $post->getId() == $id;
        });
        return $found == null ? null : new PostDto(
            $found->getId(),
            $found->getTitle(),
            $found->getBody(),
            $found->getTags(),
            $found->getComments(),
            $found->getCreatedById(),
            $found->getCreatedByName(),
            $found->getCreatedAt(),
            $found->getUpdatedAt(),
            $found->getVersion()
        );
    }

    public function findPosts(int $pageNo): Page
    {
        $from = ($pageNo - 1) * self::PAGE_SIZE;
        $to = $from + self::PAGE_SIZE;
        $slice = self::$posts->slice($from, $to)->toArray();
        $data = [];
        foreach ($slice as $item) {
            array_push($data, new PostHeaderDto(
                $item->getId(),
                $item->getTitle(),
                $item->getSummary(),
                $item->getTags(),
                count($item->getComments()),
                $item->getCreatedById(),
                $item->getCreatedByName(),
                $item->getCreatedAt()
            ));
        }
        return new Page($data, self::$posts->size(), $pageNo, self::PAGE_SIZE);
    }

    public function updateAllComments(UpdatePostCommentsDto $updatedComments, bool $append = true): void
    {
        $post = self::$posts->find(function ($post) use ($updatedComments) {
            return $post->getId() == $updatedComments->getPostId();
        });
        if ($post == null) {
            throw new \RuntimeException("Unable to Find Post For Comment");
        }
        if($append) {
            $data = $post->getComments();
            foreach ($updatedComments->getComments() as $newComment) {
                array_push($data, $newComment);
            }
            $post->setComments($data);
        } else {
            $post->setComments($updatedComments->getComments());
        }

    }

    public function findExistingPostsForBaseline(?\DateTime $from): array
    {
        //not going to be unit-tested
        return [];
    }

    public function findDeletedPostIdsForBaseline(?\DateTime $from): array
    {
        //not going to be unit-tested
        return [];
    }

    public function updateUserName(UpdatedPostsUserNameDto $updatedUserName): void
    {
        self::$posts
            ->filter(function ($post) use ($updatedUserName) {
                return $post->getCreatedByName() == $updatedUserName->getOldUserName();
            })
            ->each(function ($post) use ($updatedUserName) {
                $post->setCreatedByName($updatedUserName->getNewUserName());
            })
            ->realize();
    }
}