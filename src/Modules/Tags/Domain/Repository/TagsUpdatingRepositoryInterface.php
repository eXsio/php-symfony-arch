<?php

namespace App\Modules\Tags\Domain\Repository;

use Symfony\Component\Uid\Ulid;

interface TagsUpdatingRepositoryInterface
{
    /**
     * @param Ulid $postId
     * @param array $tags
     */
    public function updatePostTags(Ulid $postId, array $tags): void;
}