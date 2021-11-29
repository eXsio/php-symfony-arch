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
        return $id;
    }
}