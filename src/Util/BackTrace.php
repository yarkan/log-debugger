<?php

declare(strict_types=1);

namespace LDG\Util;

class BackTrace
{
    private string $file;
    private string $line;
    private string $class;
    private string $method;
    private array $collection;
    private bool $show = false;

    public function __construct(array $trace, int $index = 0)
    {
        $this->assignTrace($trace, $index);
    }

    public static function build(int $index = 0): self
    {
        return new self(debug_backtrace(), $index);
    }

    public function assignTrace(array $trace, int $index = 0): self
    {
        $this->file = (string)($trace[$index]['file'] ?? null);
        $this->line = (string)($trace[$index]['line'] ?? null);

        if (isset($trace[1])) {
            $this->class = (string)($trace[$index + 1]['class'] ?? null);
            $this->method = (string)($trace[$index + 1]['function'] ?? null);
        }

        $this->collection = $trace;
        return $this;
    }

    public function toShow(bool $enable = true): void
    {
        $this->show = $enable;
    }

    public function show(): bool
    {
        return $this->show;
    }

    private function format(): array
    {
        return [
            'file' => null,
            'line' => null,
            'class' => null,
            'method' => null,
        ];
    }

    public function process(): array
    {
        $content = [];
        foreach ($this->collection as $trace) {
            $rowTrace = $this->format();
            $rowTrace['file'] = $trace['file'] ?? null;
            $rowTrace['line'] = $trace['line'] ?? null;
            $rowTrace['class'] = $trace['class'] ?? null;
            $rowTrace['method'] = $trace['function'] ?? null;

            if (!empty($trace['object'])) {
                $rowTrace['params'][] = get_class($trace['object']);
            }

            if (!empty($trace['args'])) {
                foreach ($trace['args'] as $argument) {
                    if (is_object($argument)) {
                        $rowTrace['params'][] = get_class($argument);
                    } else {
                        $rowTrace['params'][] = gettype($argument) == 'array'
                            ? 'Array(' . count($argument) . ')' : $argument;
                    }
                }
            }

            $content[] = $rowTrace;
        }

        return $content;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function line(): string
    {
        return $this->line;
    }

    public function isFile(): bool
    {
        return !empty($this->file) && !empty($this->line);
    }

    public function class(): string
    {
        return $this->class;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function isClass(): bool
    {
        return !empty($this->class) && !empty($this->method);
    }
}
