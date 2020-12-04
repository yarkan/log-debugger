<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

class Exception implements ParseInterface
{
    public static function isSatisfiedBy($content): bool
    {
        return $content instanceof \Throwable;
    }

    public static function parse($content): string
    {
        if (!self::isSatisfiedBy($content)) {
            return Dump::parse($content);
        }

        return Line::parse($content->__toString());
    }
}
