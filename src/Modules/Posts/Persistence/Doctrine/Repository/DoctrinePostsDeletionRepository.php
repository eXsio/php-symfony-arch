<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Repository\PostsDeletionRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\Post;
use App\Modules\Posts\Persistence\Doctrine\Entity\PostComments;

class DoctrinePostsDeletionRepository extends DoctrinePostsRepository implements PostsDeletionRepositoryInterface
{

    /**
     * @param DeleteExistingPostDto $dto
     */
   public function deletePost(DeleteExistingPostDto $dto): void
    {
        $em = $this->getEntityManager();
        $em
            ->createQueryBuilder()
            ->delete(PostComments::class, 'pc')
            ->where('pc.postId = :id')
            ->setParameter('id', $dto->getId(), 'ulid')
            ->getQuery()
            ->execute();

        $em
            ->createQueryBuilder()
            ->update(Post::class, 'p')
            ->set("p.deletedAt", ":deletedAt")
            ->where('p.id = :id')
            ->setParameter('id', $dto->getId(), 'ulid')
            ->setParameter('deletedAt', new \DateTime())
            ->getQuery()
            ->execute();
    }
}