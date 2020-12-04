<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

class Line implements ParseInterface
{
    public static function isSatisfiedBy($content): bool
    {
        return !(is_object($content) || is_array($content));
    }

    public static function parse($content): string
    {
        if (!self::isSatisfiedBy($content)) {
            return Dump::parse($content);
        }

        return sprintf("%s\n", $content);
    }
}
