<?php

require_once("./lophper.inc.php");

$now = \time();

if(!$_GET["e"] && !$_GET["c"]) {
    die();
}

$event = getEvent($_GET);
$cycle = getCycle($_GET);
$lastLog = getLastLog($_SERVER);

$needsFirstLog = needsFirstLog($lastLog, $cycle, $now);
writeLog(
    getLogContent($now, $cycle),
    getLogDir($event, $now, $cycle),
    $needsFirstLog ? "$event-first" : "$event-more"
);

$responseHeader = $needsFirstLog ? createLastModifiedHeader($now) : createNotModifiedHeader();
header($responseHeader);
