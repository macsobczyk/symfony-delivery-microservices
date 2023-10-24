<?php

declare(strict_types=1);

namespace App\FedexClient;

use Throwable;
use App\FedexClient\CourierApiClientInterface;
use App\FedexClient\Exception\CourierApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FedexHttpClient implements CourierApiClientInterface {

    public function __construct(
        private HttpClientInterface $client
    ){

    }

    public function getClient() : HttpClientInterface
    {
        return $this->client;
    }

    public function getResponseCode(mixed $response) : int
    {
        return $response->getStatusCode();
    }

    public function getResponseBody(mixed $response) : array|string|null
    {
        return $response->getContent(false);
    }

    public function getResponseContentType(mixed $response) : array|string|null
    {
        return $response->getHeaders()['content-type'][0];
    }

    public function request(string $method, string $uri, mixed $data) : mixed
    {
        try {
            $response = $this->client->request($method, $uri, $data);
        } catch (Throwable $e) {
            throw new CourierApiException(
                $e->getMessage(),
                $e->getCode(),
                $data,
                $uri,
                $method
            );
        }

        if (Response::HTTP_OK !== $this->getResponseCode($response)) {
            throw new CourierApiException(
                $this->getResponseBody($response),
                $this->getResponseCode($response),
                $data,
                $uri,
                $method
            );
        }

        $responseBody = $this->getResponseBody($response);

        if (empty($responseBody)) {
            throw new CourierApiException(
                'The response body is empty',
                $this->getResponseCode($response),
                $data,
                $uri,
                $method
            );
        }

        if (!$responseBody = json_decode($responseBody, true)) {
            throw new CourierApiException(
                'API returned unsupported response body',
                $this->getResponseCode($response),
                $data,
                $uri,
                $method
            );
        }

        return $responseBody;
    }
}
