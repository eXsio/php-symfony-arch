<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Modules\Tags\Domain\Dto\TagDto;

interface TagsFindingRepositoryInterface
{

    /**
     * @return array<TagDto>
     */
    public function findTags(): array;


}