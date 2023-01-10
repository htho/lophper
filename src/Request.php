<?php

namespace Lophper;

use Exception;

class Request
{
    public readonly string $event;
    public readonly ECycles $cycle;
    public readonly int|null $lastLog;
    public readonly bool $needsLog;
    public readonly int $received;

    public function __construct(array $get, array $server, int $now)
    {
        $this->event = $this->forceValidEvent($get["e"] ?? throw new Exception("Missing get param e", 1));
        $this->cycle = $this->forceValidCycle($get["c"] ?? throw new Exception("Missing get param c", 1));
        $this->lastLog = $this->forceValidLastLog($server["HTTP_IF_MODIFIED_SINCE"] ?? null);
        $this->needsLog = $this->calcNeedsLog($now);
        $this->received = $now;
    }

    private function forceValidEvent(string $event): string
    {
        $replaced = preg_replace("/[^a-z0-9]+/", "-", $event);
        $isValid = $event === $replaced;

        if (!$isValid) {
            throw new Exception("Event contains illegal characters!", 1);
        }

        return $event;
    }

    private function forceValidCycle(string $cycle): ECycles
    {
        return ECycles::tryFrom($cycle) ?? throw new Exception("Invalid cycle characters!", 1);
    }

    private function forceValidLastLog(string|null $httpIfModifiedSince): int|null
    {
        if ($httpIfModifiedSince === null) {
            return null;
        }
        return strtotime($httpIfModifiedSince);
    }

    private function calcNeedsLog(int $now)
    {
        if ($this->lastLog === null) {
            return true;
        }

        return match ($this->cycle) {
            ECycles::ONCE => false,
            ECycles::DAILY => $this->lastLog < strtotime("-1 day", $now),
            ECycles::MONTHLY => $this->lastLog < strtotime("-1 month", $now),
        };
    }
}
