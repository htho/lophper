<?php

namespace Lophper;

$now = \time();
$request = new Request($_GET, $_SERVER);

$logWriter = new LogWriter();
$loggerFactory = new LoggerFactory($logWriter);

$sender = new HttpSender();

$lophper = new Lophper($request, $loggerFactory, $sender, $now);

$lophper->exec();
