<?php

namespace App\Tests\Modules\Security\Unit\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Domain\Dto\ChangeExistingUserPasswordDto;
use App\Modules\Security\Domain\Dto\CreateNewUserDto;
use App\Modules\Security\Domain\Dto\CreateNewUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\DeleteExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\RenameExistingUserDto;
use App\Modules\Security\Domain\Dto\UpdateExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Security\Domain\Dto\UserPostHeaderDto;
use App\Modules\Security\Domain\Repository\SecurityCommentsEventHandlingRepositoryInterface;
use App\Modules\Security\Domain\Repository\SecurityPostEventsHandlingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserCreationRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserPostHeadersFindingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserUpdatingRepositoryInterface;
use DusanKasan\Knapsack\Collection;
use Symfony\Component\Uid\Ulid;

class InMemorySecurityRepository implements
    SecurityPostEventsHandlingRepositoryInterface,
    UserPostHeadersFindingRepositoryInterface,
    UserCreationRepositoryInterface,
    SecurityCommentsEventHandlingRepositoryInterface,
    UserFindingRepositoryInterface,
    UserUpdatingRepositoryInterface
{

    private static Collection $postHeaders;
    private static Collection $users;

    public function __construct()
    {
        self::clear();
    }

    public static function clear(): void
    {
        self::$postHeaders = Collection::from([]);
        self::$users = Collection::from([
            new InMemoryUser(new Ulid(InMemoryUser::ID), "user@test.com", ["ROLE_USER"], 1)
        ]);
    }

    function createPostHeader(CreateNewUserPostHeaderDto $newPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->append(new InMemoryUserPostHeader(
            $newPostHeader->getId(),
            $newPostHeader->getTitle(),
            $newPostHeader->getSummary(),
            $newPostHeader->getTags(),
            self::$users->get(0),
            $newPostHeader->getCreatedAt(),
            $newPostHeader->getVersion(),
            $newPostHeader->getCommentsCount()
        ));
    }

    function updatePostHeader(UpdateExistingUserPostHeaderDto $updatedPostHeader): void
    {
        foreach (self::$postHeaders
                     ->filter(function ($header) use ($updatedPostHeader) {
                         return $header->getId() == $updatedPostHeader->getId() && $header->getVersion() <= $updatedPostHeader->getVersion();
                     })
                     ->toArray() as $header) {
            $header->setTitle($updatedPostHeader->getTitle());
            $header->setSummary($updatedPostHeader->getSummary());
            $header->setTags($updatedPostHeader->getTags());
            $header->setVersion($updatedPostHeader->getVersion());
        }
    }

    function deletePostHeader(DeleteExistingUserPostHeaderDto $deletedPostHeader): void
    {
        self::$postHeaders = self::$postHeaders->filter(
            function ($header) use ($deletedPostHeader) {
                return $header->getId() != $deletedPostHeader->getId();
            }
        )->realize();
    }

    function findPostHeaders(): array
    {
        return self::$postHeaders
            ->map(function ($header) {
                return new UserPostHeaderDto(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getTags(),
                    $header->getUser()->getId(),
                    $header->getUser()->getEmail(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount()
                );
            })
            ->toArray();
    }

    function createUser(CreateNewUserDto $newUser): Ulid
    {
        $id = new Ulid();
        self::$users = self::$users->append(new InMemoryUser($id, $newUser->getLogin(), $newUser->getRoles(), 1));
        return $id;
    }

    public function findPostsByUserId(Ulid $userId, int $pageNo): Page
    {
        $from = ($pageNo - 1) * self::PAGE_SIZE;
        $to = $from + self::PAGE_SIZE;
        $size = self::$postHeaders
            ->filter(function ($header) use ($userId) {
                return $header->getUser()->getId() == $userId;
            })->size();
        $data = self::$postHeaders
            ->filter(function ($header) use ($userId) {
                return $header->getUser()->getId() == $userId;
            })
            ->slice($from, $to)
            ->map(function ($header) {
                return new UserPostHeaderDto(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getTags(),
                    $header->getUser()->getId(),
                    $header->getUser()->getEmail(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount()
                );
            })
            ->toArray();
        return new Page($data, $size, $pageNo, self::PAGE_SIZE);
    }

    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void
    {
        self::$postHeaders
            ->filter(function ($header) use ($commentsCount) {
                return $header->getId() == $commentsCount->getPostId();
            })
            ->each(function ($header) use ($commentsCount) {
                $header->setCommentsCount($commentsCount->getCommentsCount());
            })
            ->realize();
    }

    public function exists(string $login): bool
    {
        return self::$users
            ->filter(function ($user) use ($login) {
                return $user->getEmail() == $login;
            })
            ->sizeIsGreaterThan(0);
    }

    public function renameUser(RenameExistingUserDto $renamedUser): void
    {
        self::$users
            ->filter(function ($user) use ($renamedUser) {
                return $user->getEmail() == $renamedUser->getCurrentLogin();
            })
            ->each(function ($user) use ($renamedUser) {
                $user->setEmail($renamedUser->getNewLogin());
            });
    }

    public function changePassword(ChangeExistingUserPasswordDto $changedPassword): void
    {
        //noop;
    }
}