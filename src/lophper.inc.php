<?php

use Exception as Exception;

function httpDateFormat(int $time): string
{
    return gmdate("D, d M Y H:i:s ", $time) . "GMT";
}

function createLastModifiedHeader($now)
{
    $lastModifiedNow = httpDateFormat($now);
    return "Last-Modified: $lastModifiedNow";
}
function createNotModifiedHeader()
{
    return "HTTP/1.1 304 Not Modified";
}

enum ECycles: string
{
    case ONCE = "o";
    case DAILY = "d";
    case MONTHLY = "m";
}

function getEvent(array $get): string
{
    $event = $get["e"] ?? throw new Exception("Missing get param e", 1);
    $replaced = preg_replace("/[^a-zA-Z0-9-_]+/", "-", $event);
    $isValid = $event === $replaced;

    if (!$isValid) {
        throw new Exception("Event contains illegal characters!", 1);
    }

    return $event;
}
function getCycle(array $get): ECycles
{
    $cycle = $get["c"] ?? throw new Exception("Missing get param c", 1);
    return ECycles::tryFrom($cycle) ?? throw new Exception("Invalid cycle characters!", 1);

}
function getLastLog(array $server): int|null
{
    $httpIfModifiedSince = $server["HTTP_IF_MODIFIED_SINCE"] ?? null;

    if ($httpIfModifiedSince === null) {
        return null;
    }

    $lastLog = strtotime($httpIfModifiedSince);

    if($lastLog === false) {
        throw new Exception("Could not parse HTTP_IF_MODIFIED_SINCE header!", 1);
    }

    return $lastLog;
}
function needsFirstLog(int|null $lastLog, ECycles $cycle, int $now): bool
{
    if ($lastLog === null) {
        return true;
    }

    return match ($cycle) {
        ECycles::ONCE => false,
        ECycles::DAILY => $lastLog < strtotime("-1 day", $now),
        ECycles::MONTHLY => $lastLog < strtotime("-1 month", $now),
    };
}


function joinDir(string ...$segments): string
{
    return join(DIRECTORY_SEPARATOR, $segments);
}

function writeLog(int $data, string $dir, string $filename)
{
    $path = joinDir($dir, $filename);
    ensureDir($dir);
    $binary = pack("C", $data);
    file_put_contents($path, $binary, FILE_APPEND | LOCK_EX);
}

function ensureDir($dir): void
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

function getLogContent(int $now, ECycles $cycle): int
{
    $dayOfMonth = intval(gmdate("j", $now));
    $hourOfDay = intval(gmdate("G", $now));
    return match ($cycle) {
        ECycles::ONCE => $dayOfMonth,
        ECycles::DAILY => $hourOfDay,
        ECycles::MONTHLY => $dayOfMonth,
    };
}

function getLogDir(string $event, int $now, ECycles $cycle): string
{
    $yearMonth = gmdate("Y-m", $now);
    $dayOfMonth = gmdate("d", $now);

    $timeSection = match ($cycle) {
        ECycles::ONCE => joinDir("once", $yearMonth),
        ECycles::DAILY => joinDir("daily", $yearMonth, $dayOfMonth),
        ECycles::MONTHLY => joinDir("monthly", $yearMonth),
    };

    return joinDir("log", $event, $timeSection);
}
