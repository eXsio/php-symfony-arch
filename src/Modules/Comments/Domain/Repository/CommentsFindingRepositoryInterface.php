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
     * @param Ulid $getPostId
     * @return array
     */
    public function findCommentsByPostId(Ulid $getPostId): array;

    /**
     * @param int $getPageNo
     * @return Page<CommentWithPostDto>
     */
    public function findLatestComments(int $getPageNo): Page;

    /**
     * @param Ulid $commentId
     * @return bool
     */
    public function commentExists(Ulid $commentId): bool;

}