<?php

namespace App\Modules\Posts\Api\Command;


class CreatePostCommand
{

    /**
     * @param string $title
     * @param string $body
     * @param array<string> $tags
     */
    public function __construct(
        private string $title,
        private string $body,
        private array  $tags
    )
    {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }


}