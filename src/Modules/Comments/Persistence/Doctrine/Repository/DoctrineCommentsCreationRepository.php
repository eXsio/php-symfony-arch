<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Modules\Comments\Domain\Dto\CreateNewCommentDto;
use App\Modules\Comments\Domain\Repository\CommentsCreationRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\Comment;
use App\Modules\Comments\Persistence\Doctrine\Entity\CommentPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineCommentsCreationRepository extends DoctrineCommentsRepository implements CommentsCreationRepositoryInterface
{

    /**
     * @param CreateNewCommentDto $newComment
     * @return Ulid
     * @throws \Doctrine\ORM\ORMException
     */
    public function createComment(CreateNewCommentDto $newComment): Ulid
    {
        $em = $this->getEntityManager();
        $comment = new Comment();
        $id = new Ulid();
        $comment->setId($id);
        $comment->setAuthor($newComment->getAuthor());
        $comment->setBody($newComment->getBody());
        $comment->setPost($em->getReference(CommentPostHeader::class, $newComment->getPostId()));
        $comment->setCreatedAt($newComment->getCreatedAt());
        if ($newComment->getParentId() != null) {
            $comment->setParentComment($em->getReference(Comment::class, $newComment->getParentId()));
        }
        $em->persist($comment);
        $em->flush();
        $this->updateCommentsCount($newComment->getPostId());
        return $id;
    }

    /**
     * @param Ulid $postId
     */
    private function updateCommentsCount(Ulid $postId): void
    {
        $em = $this->getEntityManager();
        $expr = $em->getExpressionBuilder();
        $countQuery = $em->createQueryBuilder()
            ->select($expr->count("sc.id"))
            ->from(CommentPostHeader::class, "sp")
            ->join("sp.comments","sc")
            ->where("sp.id = :id")
            ->getDQL();

        $em
            ->createQueryBuilder()
            ->update(CommentPostHeader::class, 'p')
            ->set('p.commentsCount', "($countQuery)")
            ->where('p.id = :id')
            ->setParameter("id", $postId, 'ulid')
            ->getQuery()
            ->execute();
    }
}