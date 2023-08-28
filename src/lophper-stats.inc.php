<?php

require_once("./lophper.inc.php");

function getTime(array $get): int
{
    $time = $get["t"] ?? \time();
    return intval($time);
}

function geStats(string $event, ECycles $cycle, int $time): array
{
    $logDir = getLogDir($event, $time, $cycle);

    $firstFile = joinDir($logDir, "$event-first");
    $moreFile = joinDir($logDir, "$event-more");

    return [
        "first" => simpleFilesize($firstFile),
        "more" => simpleFilesize($moreFile),
    ];
}

function simpleFilesize(string $filename): int
{
    $size = filesize($filename);
    return $size === false ? 0 : $size;
}
