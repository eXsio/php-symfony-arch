<?php

namespace App\Infrastructure\Utils;

class StringUtil
{
    /**
     * @param string|null $body
     * @param int $maxLength
     * @return string
     */
    public static function getSummary(?string $body, int $maxLength = 250): string
    {
        if ($body === null) {
            return "";
        }
        $short = strlen($body) - 1 < $maxLength ? $body : trim(substr($body, 0, $maxLength)). "...";
        return strip_tags($short) ;;
    }
}