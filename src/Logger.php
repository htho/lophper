<?php

namespace Lophper;

class Logger
{
    public function needsLog(Cycles $cycle, int $lastLog, int $now)
    {
        return match ($cycle) {
            Cycles::ONCE => $lastLog === 0,
            Cycles::ALWAYS => true,
            Cycles::DAILY => $lastLog < strtotime("-1 day", $now),
            Cycles::MONTHLY => $lastLog < strtotime("-1 month", $now),
        };
    }
}
