<?php

namespace App\Modules\Posts\Domain\Security;

use App\Infrastructure\Security\LoggedInUser;
use App\Infrastructure\Security\Permission;
use App\Infrastructure\Security\SecuredResourceAwareInterface;
use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Uid\Ulid;

class PostsVoter extends Voter
{

    public function __construct(
        private PostsApiInterface $postsApi
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof SecuredResourceAwareInterface && $subject->getResourceName() == 'post';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof LoggedInUser) {
            return false;
        }
        return match ($attribute) {
            Permission::EDIT, Permission::DELETE => $this->canPerformAction($subject->getId(), $user),
            default => true,
        };
    }

    private function canPerformAction(Ulid $postId, LoggedInUser $user): bool
    {
        $post = $this->postsApi->findPostById(new FindPostByIdQuery($postId));
        return $post->getCreatedById()->compare($user->getId()) === 0;
    }


}