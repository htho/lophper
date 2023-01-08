<?php

namespace Lophper;

$now = \time();

$request = new Request($_GET, $_SERVER);

$sender = new HttpSender();
$responseFactory = new ResponseFactory($sender, $now);

$logWriter = new LogWriter();
$loggerFactory = new LoggerFactory($logWriter, $now);

$lophper = new Lophper($request, $responseFactory, $loggerFactory);

$lophper->exec();
