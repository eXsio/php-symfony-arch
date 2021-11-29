<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Dto\TagDto;
use App\Modules\Tags\Domain\Repository\TagsFindingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;

class DoctrineTagsFindingRepository extends DoctrineTagsRepository implements TagsFindingRepositoryInterface
{
    /**
     * @return array
     */
    public function findTags(): array
    {
        $dtoClass = TagDto::class;
        $tagClass = Tag::class;
        return
            $this->getEntityManager()
                ->createQuery("select new $dtoClass(tag.tag, count(post.id)) 
                        from $tagClass tag 
                            join tag.tagPosts tp 
                            join tp.post post 
                        group by tag.tag 
                        order by count(post.id) desc")
                ->getArrayResult();
    }
}