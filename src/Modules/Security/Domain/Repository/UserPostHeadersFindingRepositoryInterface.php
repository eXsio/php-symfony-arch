<?php

namespace App\Modules\Security\Domain\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Domain\Dto\UserPostHeaderDto;
use Symfony\Component\Uid\Ulid;

interface UserPostHeadersFindingRepositoryInterface
{
    const PAGE_SIZE = 10;

    /**
     * @return array<UserPostHeaderDto>
     */
   public function findPostHeaders(): array;

    /**
     * @return Page<UserPostHeaderDto>
     */
    public function findPostsByUserId(Ulid $userId, int $pageNo): Page;

}