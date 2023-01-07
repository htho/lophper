<?php

namespace Lophper;

class DailyLogger extends AbstractLogger
{
    public function createContent(): int
    {
        $hourOfDay = intval(gmdate("G", $this->now));

        return $hourOfDay;
    }

    public function logDir(): string
    {
        $yearMonth = gmdate("Y-m", $this->now);
        $dayOfMonth = gmdate("d", $this->now);

        return $this->joinDir($yearMonth, $dayOfMonth, "daily");
    }
}