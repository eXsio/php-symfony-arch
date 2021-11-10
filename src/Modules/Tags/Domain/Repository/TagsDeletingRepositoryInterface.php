<?php

namespace App\Modules\Tags\Domain\Repository;

interface TagsDeletingRepositoryInterface
{

    public function deleteEmptyTags(): void;

}