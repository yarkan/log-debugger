<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

class Error implements ParseInterface
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

        return Line::parse(
            get_class($content) . ': ' . $content->getMessage()
            . ' in ' . $content->getFile() . ' on line ' . $content->getLine()
        );
    }
}
