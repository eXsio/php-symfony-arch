<?php

namespace App\Tests\Modules\Comments\Unit\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Dto\CommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\CommentWithPostDto;
use App\Modules\Comments\Domain\Dto\CreateNewCommentDto;
use App\Modules\Comments\Domain\Dto\CreateNewCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\DeleteExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\UpdatedCommentsPostHeadersUserNameDto;
use App\Modules\Comments\Domain\Dto\UpdateExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Repository\CommentsCreationRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsDeletionRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostsEventsHandlingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsSecurityEventsHandlingRepositoryInterface;
use DusanKasan\Knapsack\Collection;
use Symfony\Component\Uid\Ulid;

class InMemoryCommentsRepository implements
    CommentsPostsEventsHandlingRepositoryInterface,
    CommentsPostHeadersFindingRepositoryInterface,
    CommentsCreationRepositoryInterface,
    CommentsFindingRepositoryInterface,
    CommentsDeletionRepositoryInterface,
    CommentsSecurityEventsHandlingRepositoryInterface
{

    private static Collection $postHeaders;
    private static Collection $comments;

    public function __construct()
    {
        self::clear();
    }

    public static function clear(): void
    {
        self::$postHeaders = Collection::from([]);
        self::$comments = Collection::from([]);
    }

    function createPostHeader(CreateNewCommentsPostHeaderDto $newPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->append(new InMemoryCommentPostHeader(
            $newPostHeader->getId(),
            $newPostHeader->getTitle(),
            $newPostHeader->getSummary(),
            $newPostHeader->getTags(),
            $newPostHeader->getCreatedById(),
            $newPostHeader->getCreatedByName(),
            $newPostHeader->getCreatedAt(),
            $newPostHeader->getVersion()
        ));
    }

    function updatePostHeader(UpdateExistingCommentsPostHeaderDto $updatedPostHeader): void
    {
        foreach (self::$postHeaders
                     ->filter(function ($header) use ($updatedPostHeader) {
                         return $header->getId() == $updatedPostHeader->getId() && $header->getVersion() <= $updatedPostHeader->getVersion();
                     })
                     ->toArray() as $header) {
            $header->setTitle($updatedPostHeader->getTitle());
            $header->setSummary($updatedPostHeader->getSummary());
            $header->setTags($updatedPostHeader->getTags());
            $header->setVersion($updatedPostHeader->getVersion());
        }
    }

    function deletePostHeader(DeleteExistingCommentsPostHeaderDto $deletedPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->filter(
            function ($header) use ($deletedPostHeader) {
                return $header->getId() != $deletedPostHeader->getId();
            }
        )->realize();
    }

    function findPostHeaders(?\DateTime $from = null): array
    {
        return self::$postHeaders
            ->filter(function ($header) use ($from) {
                return $from == null || $header->getCreatedAt() >= $from;
            })
            ->map(function ($header) {
                return new CommentsPostHeaderDto(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getTags(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion()
                );
            })
            ->toArray();
    }

    public function createComment(CreateNewCommentDto $newComment): Ulid
    {
        $id = new Ulid();
        $parentComment = null;
        if ($newComment->getParentId() != null) {
            $parentComment = self::$comments->find(function ($comment) use ($newComment) {
                return $comment->getId() == $newComment->getParentId();
            });
        }
        $post = self::$postHeaders->find(function ($post) use ($newComment) {
            return $post->getId() == $newComment->getPostId();
        });
        $comment = new InMemoryComment(
            $id,
            $newComment->getAuthor(),
            $newComment->getBody(),
            $newComment->getCreatedAt(),
            $parentComment,
            $post
        );
        self::$comments = self::$comments->append($comment);
        $data = $post->getComments();
        array_push($data, $comment);
        $post->setComments($data);
        return $id;
    }

    public function getCommentsCount(Ulid $postId): int
    {
        return self::$postHeaders
            ->filter(function ($post) use ($postId) {
                return $post->getId() == $postId;
            })
            ->map(function ($post) {
                return count($post->getComments());
            })
            ->first();
    }

    public function findCommentsByPostId(Ulid $postId): array
    {
        $post = self::$postHeaders
            ->find(function ($post) use ($postId) {
                return $post->getId() == $postId;
            });
        if ($post == null) {
            return [];
        }
        return Collection::from($post->getComments())
            ->map(function ($comment) {
                $parentId = $comment->getParentComment() != null ? $comment->getParentComment()->getId() : null;
                return new CommentDto(
                    $comment->getId(),
                    $comment->getAuthor(),
                    $comment->getBody(),
                    $parentId,
                    $comment->getCreatedAt()
                );
            })->toArray();
    }

    public function findLatestComments(int $pageNo): Page
    {
        $from = ($pageNo - 1) * self::PAGE_SIZE;
        $to = $from + self::PAGE_SIZE;
        $data = self::$comments
            ->map(function ($comment) {
                $parentId = $comment->getParentComment() != null ? $comment->getParentComment()->getId() : null;
                return new CommentWithPostDto(
                    $comment->getId(),
                    $comment->getAuthor(),
                    $comment->getBody(),
                    $parentId,
                    $comment->getCreatedAt(),
                    $comment->getPost()->getId(),
                    $comment->getPost()->getTitle(),
                    $comment->getPost()->getSummary(),
                    self::$postHeaders
                        ->filter(function ($post) use ($comment) {
                            return $post->getId() == $comment->getPost()->getId();
                        })->size(),
                    $comment->getPost()->getTags(),

                );
            })
            ->sort(function ($c1, $c2) {
                return $c1->getCreatedAt() < $c2->getCreatedAt() ? 1 : -1;
            })
            ->slice($from, $to)
            ->toArray();
        return new Page(array_values($data), self::$comments->size(), $pageNo, self::PAGE_SIZE);
    }

    public function commentExists(Ulid $commentId): bool
    {
        return self::$comments
            ->filter(function ($comment) use ($commentId) {
                return $comment->getId() == $commentId;
            })->sizeIsGreaterThan(0);
    }

    public function postExists(Ulid $postId): bool
    {
        return self::$postHeaders
            ->filter(function ($post) use ($postId) {
                return $post->getId() == $postId;
            })->sizeIsGreaterThan(0);
    }

    public function deleteCommentsForPost(Ulid $postId)
    {
        self::$comments = self::$comments
            ->filter(function ($comment) use ($postId) {
                return $comment->getPost()->getId() != $postId;
            })
            ->realize();
        $post = self::$postHeaders->find(function ($post) use ($postId) {
            $post->getId() == $postId;
        });
        if ($post != null) {
            $post->setComments([]);
        }

    }

    public function updateUserName(UpdatedCommentsPostHeadersUserNameDto $updatedUserName): void
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
}