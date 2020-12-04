<?php

declare(strict_types=1);

namespace LDG\Logger;

class CloudWatchLog implements LoggerInterface
{
    private array $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public static function build(array $configuration): self
    {
        return new self($configuration);
    }

    public function clear(string $name): void
    {
        // TODO: Implement clear() method.
    }

    public function write(string $name, string $content): void
    {
        // TODO: Implement write() method.
    }
}
