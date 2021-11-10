<?php

namespace App\Modules\Comments\Domain\Repository;

use App\Modules\Comments\Domain\Dto\CommentsPostHeaderDto;
use Symfony\Component\Uid\Ulid;

interface CommentsPostHeadersFindingRepositoryInterface
{
    /**
     * @return array<CommentsPostHeaderDto>
     */
    public function findPostHeaders(?\DateTime $from = null): array;

    /**
     * @param Ulid $postId
     * @return bool
     */
    public function postExists(Ulid $postId): bool;

}