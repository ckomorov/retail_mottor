<?php

require "vendor/autoload.php";
require "app/RequestController.php";
require "config/config.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use RetailCrm\ApiClient;

$log = new Logger('Request');
$log->pushHandler(new StreamHandler('logs/' . date("Y-m-d"). '.log', Logger::INFO));
file_put_contents('log.log', print_r([$_REQUEST], true));
try {
    $retailClient = new ApiClient(
        RETAIL_URL,
        RETAIL_API_KEY,
        ApiClient::V5
    );
} catch (\InvalidArgumentException $e){
    $log->error("Error in create retail client: ", [$e->getMessage()]);
}

$controller = new \App\RequestController($_REQUEST, $retailClient, $log);
$controller->parseRequest();