<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Modules\Tags\Domain\Repository\TagsDeletingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsFindingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use Symfony\Component\Uid\Ulid;

class TagsUpdater
{

    /**
     * @param TagsUpdatingRepositoryInterface $tagsUpdatingRepository
     * @param TagsDeletingRepositoryInterface $tagsDeletingRepository
     */
    public function __construct(
        private TagsUpdatingRepositoryInterface $tagsUpdatingRepository,
        private TagsDeletingRepositoryInterface $tagsDeletingRepository
    )
    {
    }

    /**
     * @param Ulid $postId
     * @param array $tags
     */
   public function createUpdateTags(Ulid $postId, array $tags): void
    {
        $this->tagsUpdatingRepository->removePostFromTags($postId);
        foreach ($tags as $tag) {
            $this->tagsUpdatingRepository->addPostToTag($tag, $postId);
        }
        $this->deleteEmptyTags();
    }


   public function deleteEmptyTags(): void
    {
        $this->tagsDeletingRepository->deleteEmptyTags();
    }

}