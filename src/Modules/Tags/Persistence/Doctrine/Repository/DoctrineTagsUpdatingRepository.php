<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPost;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineTagsUpdatingRepository extends DoctrineTagsRepository implements TagsUpdatingRepositoryInterface
{

    /**
     * @param Ulid $postId
     * @param array $tags
     */
    public function updatePostTags(Ulid $postId, array $tags): void
    {
        $em = $this->getEntityManager();
        $tags = $this->fetchTags($tags);
        $post = $em->getRepository(TagPostHeader::class)->findOneBy(['id' => $postId]);
        $existentTags = [];
        foreach ($post->getTagPosts() as $tagPost) {
            $existentTag = $tagPost->getTag();
            if (!in_array($existentTag, $tags)) {
                $em->remove($tagPost);
            } else {
                array_push($existentTags, $existentTag);
            }
        }
        $em->flush();
        foreach ($tags as $tag) {
            if (!in_array($tag, $existentTags)) {
                $tagPost = new TagPost();
                $tagPost->setTag($tag);
                $tagPost->setPost($post);
                $em->persist($tagPost);
            }
        }
        $em->flush();
    }

    /**
     * @param array $tags
     * @return array
     */
    private function fetchTags(array $tags): array
    {
        $result = [];
        foreach ($tags as $tag) {
            array_push($result, $this->fetchTag($tag));
        }
        $this->getEntityManager()->flush();
        return $result;
    }

    /**
     * @param string $tag
     * @return Tag
     */
    private function fetchTag(string $tag): Tag
    {
        $result = $this->getEntityManager()
            ->getRepository(Tag::class)
            ->findOneBy(['tag' => $tag]);

        return $result != null ? $result : $this->createTag($tag);

    }

    /**
     * @param string $tag
     * @return Tag
     */
    private function createTag(string $tag): Tag
    {
        $em = $this->getEntityManager();
        $result = new Tag();
        $result->setTag($tag);
        $result->setId(new Ulid());
        $em->persist($result);
        return $result;
    }
}