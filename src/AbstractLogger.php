<?php

namespace Lophper;

abstract class AbstractLogger
{
    use JoinsDirs;

    public readonly LogWriter $logWriter;
    public readonly int $now;

    public function __construct(LogWriter $logWriter, int $now)
    {
        $this->logWriter = $logWriter;
        $this->now = $now;
    }

    public function log(string $filename): void
    {
        $data = $this->createContent();
        $dir = $this->logDir();
        $this->logWriter->write($data, $dir, $filename);
    }

    public abstract function createContent(): int;
    public abstract function logDir(): string;
}