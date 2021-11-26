<?php

namespace App\Modules\Comments\Domain\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Domain\Dto\CommentWithPostDto;
use Symfony\Component\Uid\Ulid;

interface CommentsFindingRepositoryInterface
{

    const PAGE_SIZE = 10;

    /**
     * @param Ulid $postId
     * @return int
     */
    public function getCommentsCount(Ulid $postId): int;

    /**
     * @param Ulid $postId
     * @return array
     */
    public function findCommentsByPostId(Ulid $postId): array;

    /**
     * @param int $pageNo
     * @return Page<CommentWithPostDto>
     */
    public function findLatestComments(int $pageNo): Page;

    /**
     * @param Ulid $commentId
     * @return bool
     */
    public function commentExists(Ulid $commentId): bool;

}