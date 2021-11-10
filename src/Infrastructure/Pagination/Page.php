<?php

namespace App\Infrastructure\Pagination;

/**
 * Page<T>
 */
class Page
{

    /**
     * @param array $data
     * @param int $count
     * @param int $pageNo
     */
    public function __construct(
        private array $data,
        private int   $count,
        private int   $pageNo,
        private int   $pageSize
    )
    {
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPageNo(): int
    {
        return $this->pageNo;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }


}