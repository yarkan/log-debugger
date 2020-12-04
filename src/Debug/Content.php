<?php

declare(strict_types=1);

namespace LDG\Debug;

use LDG\Debug\Parse\ParseMapping;
use LDG\Util\BackTrace;

class Content
{
    public static function header(BackTrace $trace, string $content): string
    {
        $dateTime = new \DateTime('now');
        $header = $dateTime->format('Y-m-d\TH:i:s' . substr((string)microtime(false), 1, 8) . 'O');
        $header .= self::separator('-', 48, null, null);

        if ($trace->isClass()) {
            $header .= sprintf("\nNAMESPACE : %s::%s(LINE : %s);", $trace->class(), $trace->method(), $trace->line());
        } elseif ($trace->isFile()) {
            $header .= sprintf("\nPATH    : %s", $trace->file());
            $header .= sprintf("\nLINE    : %s", $trace->line());
        }

        return sprintf("%s\n%s\n", $header, $content);
    }

    public static function generate(BackTrace $trace, string $type, array $arguments): string
    {
        $content = [];
        if ($trace->show()) {
            $content[] = ParseMapping::parse(ParseMapping::PRINT_R, $trace->process());
        }

        foreach ($arguments as $argument) {
            $content[] = ParseMapping::parse($type, $argument);
        }

        return self::header($trace, implode(self::separator(), $content));
    }

    private static function separator(
        string $separator = '-',
        int $quantity = 80,
        ?string $leftBreak = "\n",
        ?string $rightBreak = "\n"
    ): string {
        return sprintf(
            "%s%s%s",
            $leftBreak,
            str_pad('', $quantity, $separator, STR_PAD_RIGHT),
            $rightBreak
        );
    }
}
