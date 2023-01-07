<?php

namespace Lophper;

class Logger
{
    use JoinsDirs;
    public readonly ECycles $cycle;
    public readonly int $lastLog;
    public readonly int $now;
    public readonly LogWriter $logWriter;

    public function __construct(Request $request, LogWriter $logWriter, int $now)
    {
        $this->cycle = $request->cycle;
        $this->lastLog = $request->lastLog;

        $this->logWriter = $logWriter;
        $this->now = $now;
    }

    public function needsLog(): bool
    {
        return match ($this->cycle) {
            ECycles::ONCE => $this->lastLog === 0,
            ECycles::DAILY => $this->lastLog < strtotime("-1 day", $this->now),
            ECycles::MONTHLY => $this->lastLog < strtotime("-1 month", $this->now),
        };
    }

    public function log(string $event): void
    {
        $dir = $this->logDir();
        $data = $this->createContent();
        $filename = $event;
        $this->logWriter->write($data, $dir, $filename);
    }

    public function createContent(): int
    {
        $dayOfMonth = intval(gmdate("j", $this->now));
        $hourOfDay = intval(gmdate("G", $this->now));
        return match ($this->cycle) {
            ECycles::ONCE => $dayOfMonth,
            ECycles::DAILY => $hourOfDay,
            ECycles::MONTHLY => $dayOfMonth,
        };
    }

    public function logDir(): string
    {
        $yearMonth = gmdate("Y-m", $this->now);
        $dayOfMonth = gmdate("d", $this->now);

        return match ($this->cycle) {
            ECycles::ONCE => $this->joinDir($yearMonth, "once"),
            ECycles::DAILY => $this->joinDir($yearMonth, $dayOfMonth, "daily"),
            ECycles::MONTHLY => $this->joinDir($yearMonth, "monthly"),
        };
    }
}
