<?php

require_once("./lophper.inc.php");
require_once("./lophper-stats.inc.php");

$event = getEvent($_GET);
$cycle = getCycle($_GET);
$time = getTime($_GET);

header("Content-Type: application/json; charset=utf-8");
print_r(json_encode(geStats($event, $cycle, $time)));
