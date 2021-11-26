<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use Symfony\Component\Uid\Ulid;

interface PostsCreationRepositoryInterface
{
    /**
     * @param CreateNewPostDto $newPost
     * @return Ulid - id of the new Post
     */
   public function createPost(CreateNewPostDto $newPost): Ulid;
}