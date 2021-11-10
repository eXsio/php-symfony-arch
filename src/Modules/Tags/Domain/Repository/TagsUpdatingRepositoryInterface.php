<?php

namespace App\Modules\Tags\Domain\Repository;

use Symfony\Component\Uid\Ulid;

interface TagsUpdatingRepositoryInterface
{
    /**
     * @param string $tag
     * @param Ulid $postId
     */
    public function addPostToTag(string $tag, Ulid $postId): void;

    /**
     * @param Ulid $postId
     */
    public function removePostFromTags(Ulid $postId): void;
}