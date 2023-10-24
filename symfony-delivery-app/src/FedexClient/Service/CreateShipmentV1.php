<?php

namespace App\FedexClient\Service;

use App\FedexClient\Model\FedexApi;
use App\FedexClient\Service\AbstractWebService;
use App\FedexClient\Service\WebServiceInterface;

class CreateShipmentV1 extends AbstractWebService implements WebServiceInterface
{
    /**
     * Sends shipment request to Fedex
     *
     * @param array $headers Request headers
     * @param array $body Request body
     * @return string
     */
    public function __invoke(array $headers, array $body) : array
    {
        $this->model = FedexApi::CreateShipmentV1;
        $response = $this->request($headers, $body);

        return $this->response;
    }
}

