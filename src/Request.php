<?php

namespace Lophper;

use Exception;

class Request
{
    public readonly string $event;
    public readonly ECycles $cycle;
    public readonly int $lastLog;

    public function __construct(array $get, array $server)
    {
        $this->event = $this->forceValidEvent($get["e"] ?? throw new Exception("Missing get param e", 1));
        $this->cycle = $this->forceValidCycle($get["c"] ?? throw new Exception("Missing get param c", 1));
        $this->lastLog = $this->forceValidLastLog($server["HTTP_IF_MODIFIED_SINCE"] ?? null);
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
}
