<?php

namespace App\FedexClient;

interface CourierApiClientInterface {

    public function getResponseCode(mixed $response) : int;

    public function getResponseBody(mixed $response) : array|string|null;

    public function getResponseContentType(mixed $response) : array|string|null;

    public function request(string $method, string $uri, mixed $data) : mixed;
}
