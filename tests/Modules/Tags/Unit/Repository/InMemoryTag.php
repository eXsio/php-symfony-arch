<?php

namespace App\Tests\Modules\Tags\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryTag
{
    /**
     * @param Ulid $id
     * @param string $tag
     */
    public function __construct(
        private Ulid              $id,
        private string            $tag)
    {
    }

    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }




}