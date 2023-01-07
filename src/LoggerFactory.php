<?php

namespace Lophper;

class LoggerFactory
{
    public readonly LogWriter $logWriter;
    public function __construct(LogWriter $logWriter) {
        $this->logWriter = $logWriter;
    }
    public function getLogger(ECycles $cycle, int $now): AbstractLogger
    {
        return match ($cycle) {
            ECycles::ONCE => new OnceLogger($this->logWriter, $now),
            ECycles::DAILY => new DailyLogger($this->logWriter, $now),
            ECycles::MONTHLY => new MonthlyLogger($this->logWriter, $now),
        };
    }
}