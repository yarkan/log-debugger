<?php

declare(strict_types=1);

namespace LDG\Debug;

use LDG\Debug\Parse\ParseMapping;
use LDG\Logger\LoggerInterface;
use LDG\Util\BackTrace;

class Structure
{
    private LoggerInterface $logger;
    private BackTrace $trace;
    private string $name;
    private bool $clear = false;
    private ?string $memory = null;

    public function __construct(LoggerInterface $logger, BackTrace $trace)
    {
        $this->logger = $logger;
        $this->trace = $trace;
    }

    public static function build(LoggerInterface $log, BackTrace $trace): self
    {
        return new self($log, $trace);
    }

    public function name(string $name): self
    {
        if (preg_match('/^([a-zA-Z0-9]{1})([a-zA-Z0-9\.\-_\(\)])*$/', $name) == 1) {
            $this->name = (string)$name;
        }

        return $this;
    }

    public function showTrace(): self
    {
        $this->trace->toShow();
        return $this;
    }

    public function clear(): self
    {
        $this->clear = true;
        return $this;
    }

    public function memory(string $memory): self
    {
        if (preg_match('/^([1-9]{1})([0-9])*([M]{1})$/', $memory) == 1) {
            $this->memory = $memory;
        }

        return $this;
    }

    public function trace(): void
    {
        $this->showTrace();
        $this->process(ParseMapping::LINE, []);
    }

    public function line(...$args): void
    {
        $this->process(ParseMapping::LINE, $args);
    }

    public function json(...$args): void
    {
        $this->process(ParseMapping::JSON, $args);
    }

    public function impl(...$args): void
    {
        $this->process(ParseMapping::IMPLEMENT, $args);
    }

    public function error(...$args): void
    {
        $this->process(ParseMapping::ERROR, $args);
    }

    public function exception(...$args): void
    {
        $this->process(ParseMapping::EXCEPTION, $args);
    }

    public function print(...$args): void
    {
        $this->process(ParseMapping::PRINT_R, $args);
    }

    public function dump(...$args): void
    {
        $this->process(ParseMapping::DUMP, $args);
    }

    private function process(string $type, array $arguments): void
    {
        $memory = $this->expandMemory();

        $this->processClear();

        $this->logger->write($this->name, Content::generate($this->trace, $type, $arguments));

        $this->restoreMemory($memory);
    }

    private function expandMemory(): ?string
    {
        if (empty($this->memory)) {
            return null;
        }

        $memory = ini_get('memory_limit');
        ini_set('memory_limit', $this->memory);
        return $memory;
    }

    private function processClear(): void
    {
        if ($this->clear) {
            $this->logger->clear($this->name);
        }
    }

    private function restoreMemory(?string $memory): void
    {
        if (!empty($memory)) {
            ini_set('memory_limit', $memory);
        }
    }
}
