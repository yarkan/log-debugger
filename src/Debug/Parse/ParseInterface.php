<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

interface ParseInterface
{
    public static function isSatisfiedBy($content): bool;
    public static function parse($content): string;
}
