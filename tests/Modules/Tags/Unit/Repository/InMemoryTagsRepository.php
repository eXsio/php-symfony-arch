<?php

namespace App\Tests\Modules\Tags\Unit\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Domain\Dto\CreateNewTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\DeleteExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\TagDto;
use App\Modules\Tags\Domain\Dto\TagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\UpdatedTagsPostHeadersUserNameDto;
use App\Modules\Tags\Domain\Dto\UpdateExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Tags\Domain\Repository\TagsCommentsEventHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsDeletingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsFindingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsPostEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsPostHeadersFindingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use DusanKasan\Knapsack\Collection;
use Symfony\Component\Uid\Ulid;

class InMemoryTagsRepository implements
    TagsPostEventsHandlingRepositoryInterface,
    TagsPostHeadersFindingRepositoryInterface,
    TagsFindingRepositoryInterface,
    TagsDeletingRepositoryInterface,
    TagsUpdatingRepositoryInterface,
    TagsCommentsEventHandlingRepositoryInterface,
    TagsSecurityEventsHandlingRepositoryInterface
{

    private static Collection $postHeaders;
    private static Collection $tags;

    public function __construct()
    {
        self::clear();
    }

    public static function clear(): void
    {
        self::$postHeaders = Collection::from([]);
        self::$tags = Collection::from([]);
    }

    public function createPostHeader(CreateNewTagsPostHeaderDto $newPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->append(new InMemoryTagPostHeader(
            $newPostHeader->getId(),
            $newPostHeader->getTitle(),
            $newPostHeader->getSummary(),
            $newPostHeader->getCreatedById(),
            $newPostHeader->getCreatedByName(),
            $newPostHeader->getCreatedAt(),
            $newPostHeader->getVersion(),
            $newPostHeader->getCommentsCount()
        ));
    }

    public function updatePostHeader(UpdateExistingTagsPostHeaderDto $updatedPostHeader): void
    {
        self::$postHeaders
            ->filter(function ($header) use ($updatedPostHeader) {
                return $header->getId() == $updatedPostHeader->getId() && $header->getVersion() <= $updatedPostHeader->getVersion();
            })
            ->each(function ($header) use ($updatedPostHeader) {
                $header->setTitle($updatedPostHeader->getTitle());
                $header->setSummary($updatedPostHeader->getSummary());
                $header->setVersion($updatedPostHeader->getVersion());
            })->realize();
    }

    public function deletePostHeader(DeleteExistingTagsPostHeaderDto $deletedPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->filter(
            function ($header) use ($deletedPostHeader) {
                return $header->getId() != $deletedPostHeader->getId();
            }
        )->realize();
    }

    public function findPostHeaders(): array
    {
        return self::$postHeaders
            ->map(function ($header) {
                return new TagsPostHeaderDto(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount(),
                    Collection::from($header->getTags())
                        ->map(function ($tag) {
                            return $tag->getTag();
                        })
                        ->toArray()
                );
            })
            ->toArray();
    }

    public function deleteEmptyTags(): void
    {
        $usedTagIds = $this->findUsedTagIds();
        self::$tags = self::$tags
            ->filter(function ($tag) use ($usedTagIds) {
                return $usedTagIds->contains($tag->getTag());
            })->realize();
    }

    private function findUsedTagIds(): ArrayCollection
    {
        $usedTags = new ArrayCollection();
        self::$postHeaders->each(function ($post) use ($usedTags) {
            foreach ($post->getTags() as $tag) {
                $usedTags->add($tag->getTag());
            }
        })->realize();
        return $usedTags;
    }

    public function findTags(): array
    {
        return array_values(self::$tags->map(function ($tag) {
            return new TagDto($tag->getTag(), $this->countPosts($tag));
        })->sort(function ($tag1, $tag2) {
            return $tag1->getPostsCount() > $tag2->getPostsCount();
        })->toArray());
    }

    public function findPostHeadersByTag(string $tag, int $pageNo): Page
    {
        $from = ($pageNo - 1) * self::PAGE_SIZE;
        $to = $from + self::PAGE_SIZE;
        $size = self::$postHeaders
            ->filter(function ($post) use ($tag) {
                return Collection::from($post->getTags())
                        ->filter(function ($postTag) use ($tag) {
                            return $postTag->getTag() == $tag->getTag();
                        })
                        ->size() > 0;
            })->size();
        $data = array_values(self::$postHeaders
            ->filter(function ($post) use ($tag) {
                return Collection::from($post->getTags())
                        ->filter(function ($postTag) use ($tag) {
                            return $postTag->getTag() == $tag->getTag();
                        })
                        ->size() > 0;
            })
            ->slice($from, $to)
            ->map(function ($header) {
                return new TagsPostHeaderDto(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount(),
                    Collection::from($header->getTags())
                        ->map(function ($tag) {
                            return $tag->getTag();
                        })
                        ->toArray()
                );
            })
            ->toArray());
        return new Page($data, $size, $pageNo, self::PAGE_SIZE);
    }

    private function countPosts(InMemoryTag $tag): int
    {
        return self::$postHeaders
            ->filter(function ($post) use ($tag) {
                return Collection::from($post->getTags())
                        ->filter(function ($postTag) use ($tag) {
                            return $postTag->getTag() == $tag->getTag();
                        })
                        ->size() > 0;
            })
            ->size();
    }

    public function updatePostTags(Ulid $postId, array $tags): void
    {
        self::$postHeaders
            ->filter(function ($post) use ($postId) {
                return $post->getId() == $postId;
            })
            ->each(function ($post) {
                $post->setTags([]);
            })->realize();
        foreach ($tags as $tag) {
            $this->addPostToTag($tag, $postId);
        }
    }

    private function addPostToTag(string $tag, Ulid $postId): void
    {
        $tagObj = self::$tags->find(function ($tagObj) use ($tag) {
            return $tagObj->getTag() == $tag;
        });
        if ($tagObj == null) {
            $tagObj = new InMemoryTag(new Ulid(), $tag);
            self::$tags = self::$tags->append($tagObj);
        }
        $post = self::$postHeaders->find(function ($post) use ($postId) {
            return $post->getId() == $postId;
        });
        if ($post != null) {
            $tags = $post->getTags();
            array_push($tags, $tagObj);
            $post->setTags($tags);
        } else {
            throw new \RuntimeException("No Post Header: " . $postId);
        }
    }

    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void
    {
        self::$postHeaders
            ->filter(function ($header) use ($commentsCount) {
                return $header->getId() == $commentsCount->getPostId();
            })
            ->each(function ($header) use ($commentsCount) {
                $header->setCommentsCount($commentsCount->getCommentsCount());
            })
            ->realize();
    }

    public function updateUserName(UpdatedTagsPostHeadersUserNameDto $updatedUserName): void
    {
        self::$postHeaders
            ->filter(function ($post) use ($updatedUserName) {
                return $post->getCreatedByName() == $updatedUserName->getOldUserName();
            })
            ->each(function ($post) use ($updatedUserName) {
                $post->setCreatedByName($updatedUserName->getNewUserName());
            })
            ->realize();
    }

    public function postExists(Ulid $postId): bool
    {
        return self::$postHeaders
            ->filter(function ($post) use ($postId) {
                return $post->getId() == $postId;
            })->sizeIsGreaterThan(0);
    }
}