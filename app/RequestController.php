<?php

namespace App;

use Monolog\Logger;
use RetailCrm\ApiClient;
use RetailCrm\Exception\CurlException;

class RequestController
{
    /** @var array */
    public $request;

    /** @var Logger */
    public $logger;

    /** @var ApiClient  */
    public $client;

    public function __construct(array $request, ApiClient $client, Logger $logger)
    {
        $this->request = $request;
        $this->client = $client;
        $this->logger = $logger;
    }

    public function parseRequest(): array
    {
        $data = $this->request;
        $this->logger->info("New Request from LpMotor: ", [$this->request]);

        try {
            $newOrder = $this->createOrderInRetail($data, $this->client);
        } catch (\Exception $e) {

        }

        return $newOrder ?? [];
    }

    private function createOrderInRetail(array $request, ApiClient $client): array
    {
        try {
            $response = $client->request->ordersCreate(array(
                'externalId' => $request['id_lead'],
                'number' => $request['id_lead'],
                'firstName' => $request['name'] ?? 'no name',
                'email' => $request['email'] ?? '',
                'phone' => $request['phone'],
                'customerComment' => $request['frm_title'],
            ));
        } catch (\RetailCrm\Exception\CurlException $e) {
            $this->logger->error(
                date("Y-m-d H:i:s: ") . "Connection error: ",
                [json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE)]
            );
        }

        if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
            $this->logger->info(
                'Order successfully created.',
                ['Order ID into RetailCRM = ' . $response->id]
            );
        } else {
            $this->logger->error(
                sprintf("Error: [HTTP-code %s]", $response->getStatusCode()),
                [$response['errors'] ? $response['errors']: 'Response has no errors']
            );
        }
        return [];
    }

}