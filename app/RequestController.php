<?php

namespace App;

use Monolog\Logger;

class RequestController
{
    /** @var array */
    public $request;

    /** @var Logger */
    public $logger;

    public function __construct(array $request, Logger $logger)
    {
        $this->request = $request;
        $this->logger = $logger;
    }

    public function parseRequest(): array
    {
        $result = $this->request;

        $this->logger->info(date("Y-m-d H:i:s: "), [json_encode($this->request)]);

        return $result;
    }
}