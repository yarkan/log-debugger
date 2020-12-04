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

    public function line(): void
    {
        $this->process(ParseMapping::LINE, func_get_args());
    }

    public function json(): void
    {
        $this->process(ParseMapping::JSON, func_get_args());
    }

    public function impl(): void
    {
        $this->process(ParseMapping::IMPLEMENT, func_get_args());
    }

    public function error(): void
    {
        $this->process(ParseMapping::ERROR, func_get_args());
    }

    public function exception(): void
    {
        $this->process(ParseMapping::EXCEPTION, func_get_args());
    }

    public function print(): void
    {
        $this->process(ParseMapping::PRINT_R, func_get_args());
    }

    public function dump(): void
    {
        $this->process(ParseMapping::DUMP, func_get_args());
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
