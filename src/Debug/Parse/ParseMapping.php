<?php

declare(strict_types=1);

namespace LDG\Debug\Parse;

class ParseMapping
{
    public const LINE = 'line';
    public const JSON = 'json';
    public const IMPLEMENT = 'impl';
    public const ERROR = 'error';
    public const EXCEPTION = 'exception';
    public const PRINT_R = 'print';
    public const DUMP = 'dump';

    public static function classNames(): array
    {
        return [
            self::LINE => Line::class,
            self::JSON => Json::class,
            self::IMPLEMENT => Implement::class,
            self::ERROR => Error::class,
            self::EXCEPTION => Exception::class,
            self::PRINT_R => PrintR::class,
            self::DUMP => Dump::class,
        ];
    }

    public static function parse(string $type, $content): string
    {
        /** @var ParseInterface $className */
        $className = self::classNames()[$type] ?? self::classNames()[self::DUMP];

        return $className::parse($content);
    }
}
