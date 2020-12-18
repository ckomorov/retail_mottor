<?php

require "vendor/autoload.php";
require "app/RequestController.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('Request');
$log->pushHandler(new StreamHandler('logs/' . date("Y-m-d"). '.log', Logger::INFO));

$controller = new \App\RequestController($_REQUEST, $log);
$controller->parseRequest();