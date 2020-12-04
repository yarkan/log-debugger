<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

class Implement implements ParseInterface
{
    public static function isSatisfiedBy($content): bool
    {
        return is_object($content);
    }

    public static function parse($content): string
    {
        if (!self::isSatisfiedBy($content)) {
            return Dump::parse($content);
        }

        return PrintR::parse(
            [
                'class' => get_class($content),
                'parent' => get_parent_class($content),
                'implements' => class_implements($content),
                'hast' => spl_object_hash($content),
                'methods' => get_class_methods($content),
            ]
        );
    }
}
