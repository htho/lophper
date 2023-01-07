<?php

namespace Lophper;

$now = \time();
$logWriter = new LogWriter();
$request = new Request($_GET, $_SERVER);
$logger = new Logger($request, $logWriter, $now);
$sender = new HttpSender();

$lophper = new Lophper($request, $logger, $sender, $now);

$lophper->exec();
