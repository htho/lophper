<?php

namespace Lophper;

use Exception;

class Request
{
    public readonly string $event;
    public readonly ECycles $cycle;
    public readonly int $lastLog;
    public readonly bool $isFresh;
    public readonly int $received;

    public function __construct(array $get, array $server, int $now)
    {
        $this->event = $this->forceValidEvent($get["e"] ?? throw new Exception("Missing get param e", 1));
        $this->cycle = $this->forceValidCycle($get["c"] ?? throw new Exception("Missing get param c", 1));
        $this->lastLog = $this->forceValidLastLog($server["HTTP_IF_MODIFIED_SINCE"] ?? null);
        $this->isFresh = $this->calcIsFresh($now);
        $this->received = $now;
    }

    public function forceValidEvent(string $event): string
    {
        $replaced = preg_replace("/[^a-z0-9]+/", "-", $event);
        $isValid = $event === $replaced;

        if (!$isValid) {
            throw new Exception("Event contains illegal characters!", 1);
        }

        return $event;
    }

    public function forceValidCycle(string $cycle): ECycles
    {
        return ECycles::tryFrom($cycle) ?? throw new Exception("Invalid cycle characters!", 1);
    }

    public function forceValidLastLog(string|null $httpIfModifiedSince): int
    {
        if ($httpIfModifiedSince == null) {
            return 0;
        }
        return strtotime($httpIfModifiedSince);
    }

    private function calcIsFresh(int $now)
    {
        return match ($this->cycle) {
            ECycles::ONCE => $this->lastLog === 0,
            ECycles::DAILY => $this->lastLog < strtotime("-1 day", $now),
            ECycles::MONTHLY => $this->lastLog < strtotime("-1 month", $now),
        };
    }
}
