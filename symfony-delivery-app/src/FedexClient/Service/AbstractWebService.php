<?php

namespace App\FedexClient\Service;

use App\FedexClient\CourierApiClientInterface;
use App\FedexClient\Model\FedexApi;
use App\FedexClient\Service\WebServiceInterface;
use UnhandledMatchError;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

abstract class AbstractWebService implements WebServiceInterface  {

    /**
     * @var FedexApi
     */
    protected FedexApi $model;

    /**
     * @var string API request method
     */
    protected string $method = "";

    /**
     * @var array API response
     */
    protected array $response = [];

    /**
     * @var array API request headers
     */
    protected array $headers = [];

    /**
     * @var array API request body
     */
    protected array $body = [];

    public function __construct(
        protected CourierApiClientInterface $client,

        #[Autowire('%fedex_grant_type%')]
        protected readonly string $grantType,

        #[Autowire('%fedex_client_id%')]
        protected readonly string $clientId,

        #[Autowire('%fedex_client_secret%')]
        protected readonly string $clientSecret,

        /** @var bool Indicates whether client works in prod or sandbox environment */
        #[Autowire('%fedex_prod%')]
        protected bool $prod,
    ) {}

    /**
     * Gets current prod|sandbox settings
     *
     * @return array
     */
    public function getProd() : bool
    {
        return $this->prod;
    }

    /**
     * @inheritDoc
     */
    public function getResponse() : array
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getBody() : array
    {
        return $this->body;
    }

    /**
     * Gets the API request method
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Gets the API Service Model
     * @return FedexApi
     * @throws UnhandledMatchError
     */
    public function getModel() : FedexApi
    {
        return $this->model;
    }

    /**
     * Gets the API client
     * @return CourierApiClientInterface
     */
    public function getClient() : CourierApiClientInterface
    {
        return $this->client;
    }

    public function getDataKey() : string
    {
        return $this->model->dataKey();
    }

    /**
     * Makes the API request
     *
     * @param array $headers Request headers
     * @param array $body Request body
     * @param CourierApiClientInterface $client
     * @return array
     */
    protected function request(array $headers, array $body) : array
    {
        $this->headers = array_merge($this->getModel()->headers(), $headers);
        $this->body = $body;

        $this->response = $this->getClient()->request(
            $this->getModel()->method(),
            $this->getModel()->getUrl($this->prod, $this->model->version()),
            [
                'headers' => $this->headers,
                'json' => $this->body,
            ]
        );

        return $this->response;
    }
}

