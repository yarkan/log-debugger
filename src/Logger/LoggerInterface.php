<?php

declare(strict_types=1);

namespace LDG\Logger;

interface LoggerInterface
{
    public function clear(string $name): void;

    public function write(string $name, string $content): void;
}
