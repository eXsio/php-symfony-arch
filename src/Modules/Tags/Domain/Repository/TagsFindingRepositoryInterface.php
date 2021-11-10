<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Modules\Tags\Domain\Dto\TagDto;
use Symfony\Component\Uid\Ulid;

interface TagsFindingRepositoryInterface
{

    /**
     * @return array<TagDto>
     */
    public function findTags(): array;


}