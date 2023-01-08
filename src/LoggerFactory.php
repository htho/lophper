<?php

namespace Lophper;

class LoggerFactory
{
    public readonly LogWriter $logWriter;
    public readonly int $now;

    public function __construct(LogWriter $logWriter, int $now) {
        $this->logWriter = $logWriter;
        $this->now = $now;
    }
    public function getLogger(ECycles $cycle): AbstractLogger
    {
        return match ($cycle) {
            ECycles::ONCE => new OnceLogger($this->logWriter, $this->now),
            ECycles::DAILY => new DailyLogger($this->logWriter, $this->now),
            ECycles::MONTHLY => new MonthlyLogger($this->logWriter, $this->now),
        };
    }
}