<?php

namespace App\Modules\Tags\Api\Query\Response;

class FindTagsQueryResponse
{
    /**
     * @param string $tag
     * @param int $postsCount
     */
    public function __construct(
        private string $tag,
        private int    $postsCount
    )
    {
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getPostsCount(): int
    {
        return $this->postsCount;
    }


}