<?php

namespace Lophper;

class OnceLogger extends AbstractLogger
{
    public function createContent(): int
    {
        $dayOfMonth = intval(gmdate("j", $this->now));

        return $dayOfMonth;
    }

    public function logDir(): string
    {
        $yearMonth = gmdate("Y-m", $this->now);

        return $this->joinDir($yearMonth, "once");

    }
}