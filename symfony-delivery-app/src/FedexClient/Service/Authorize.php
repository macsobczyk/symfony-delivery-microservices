<?php

namespace App\FedexClient\Service;

use App\FedexClient\Model\FedexApi;
use App\FedexClient\Service\AbstractWebService;
use App\FedexClient\Service\WebServiceInterface;

class Authorize extends AbstractWebService implements WebServiceInterface
{

    /**
     * Authorizes the API client
     * @return string
     */
    public function __invoke() : string
    {

        $this->model = FedexApi::Authorize;
        $this->headers = $this->model->headers();
        $method = $this->model->getUrl($this->prod, $this->model->version());

        $this->body = [
            'grant_type' => $this->grantType,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $this->response = $this->client->request(
            $this->model->method(),
            $method,
            [
                'headers' => $this->headers,
                'body' => $this->body
            ]
        );

        return $this->response[$this->model->dataKey()];
    }

}
