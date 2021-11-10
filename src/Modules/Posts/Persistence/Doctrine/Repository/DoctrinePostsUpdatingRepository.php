<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;
use App\Modules\Posts\Domain\Repository\PostsUpdatingRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePostsUpdatingRepository extends DoctrinePostsRepository implements PostsUpdatingRepositoryInterface
{

    /**
     * @param UpdateExistingPostDto $dto
     */
    function updatePost(UpdateExistingPostDto $dto): void
    {

        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(Post::class, 'p')
            ->set('p.title', ':title')
            ->set('p.summary', ':summary')
            ->set('p.body', ':body')
            ->set('p.tags', ':tags')
            ->where('p.id = :id')
            ->setParameter("id", $dto->getId(), 'ulid')
            ->setParameter("title", $dto->getTitle())
            ->setParameter("summary", $dto->getSummary())
            ->setParameter("body", $dto->getBody())
            ->setParameter("tags", json_encode($dto->getTags()))
            ->getQuery()
            ->execute();
    }
}