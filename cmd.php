<?php
namespace Lophper;
require_once("./autoload.php");

if ($argc < 3) {
    throw new \Exception("not enugh arguments", 1);
}


$get = ["e" => $argv[1], "c" => $argv[2]];
$server = [];
if (isset($argv[3])) {
    $time = strtotime($argv[3]);
    $httpDate = new HttpDateFormatter($time);
    $server["HTTP_IF_MODIFIED_SINCE"] = $httpDate->httpDate;
}

class CmdSender implements Sender {
    public function header(string $data) {
        echo("HEADER: '$data'");
    }
    public function echo(string $data) {
        echo("ECHO: '$data'");
    }
}

class CmdLogWriter extends LogWriter
{
    public function write(int $data, string $dir, string $filename)
    {
        \print_r(["data" => $data, "dir" => $dir, "filename" => $filename]);
    }
}

$now = \time();
$logWriter = new CmdLogWriter();
$request = new Request($get, $server);
$logger = new Logger($request, $logWriter, $now);
$sender = new CmdSender();

$lophper = new Lophper($request, $logger, $sender, $now);

$lophper->exec();