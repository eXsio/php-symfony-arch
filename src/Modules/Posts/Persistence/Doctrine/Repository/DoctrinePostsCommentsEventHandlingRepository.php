<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Modules\Posts\Domain\Dto\UpdatePostCommentsDto;
use App\Modules\Posts\Domain\Repository\PostsCommentsEventHandlingRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\PostComments;

class DoctrinePostsCommentsEventHandlingRepository extends DoctrinePostsRepository implements PostsCommentsEventHandlingRepositoryInterface
{
    public function updateAllComments(UpdatePostCommentsDto $updatedComments, bool $append = true): void
    {
        $comments =
            $this->getEntityManager()
                ->getRepository(PostComments::class)
                ->findOneBy(["postId" => $updatedComments->getPostId()]);
        if($append) {
            $data = $comments->getComments();
            foreach ($updatedComments->getComments() as $newComment) {
                array_push($data, $newComment);
            }
            $comments->setComments($data);
        } else {
            $comments->setComments($updatedComments->getComments());
        }

        $comments->setCommentsCount(count($comments->getComments()));
    }
}