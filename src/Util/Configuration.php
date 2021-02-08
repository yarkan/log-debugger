<?php

declare(strict_types=1);

namespace LDG\Util;

class Configuration
{
    public const NAME = 'LDG_NAME';
    public const ROUTE = 'LDG_ROUTE';
    public const ROUTE_LEVEL = 'LDG_ROUTE_LEVEL';

    public static function route(): string
    {
        $level = (int)($_ENV[self::ROUTE_LEVEL] ?? 5);
        return \dirname(__DIR__, $level) . $_ENV[self::ROUTE] ?? '/var/log';
    }

    public static function name(): string
    {
        return $_ENV[self::NAME] ?? 'Warning';
    }
}
