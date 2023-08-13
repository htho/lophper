<?php

require_once("./lophper.inc.php");

$now = \time();

$event = getEvent($_GET);
$cycle = getCycle($_GET);
$lastLog = getLastLog($_SERVER);

$needsLog = getNeedsLog($lastLog, $cycle, $now);
writeLog(
    getLogContent($now, $cycle),
    getLogDir($event, $now, $cycle),
    $needsLog ? "$event-first" : "$event-more"
);

$responseHeader = $needsLog ? createLastModifiedHeader($now) : createNotModifiedHeader();
header($responseHeader);