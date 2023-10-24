<?php

namespace App\FedexClient\Service;

interface WebServiceInterface {

    /**
     * Gets the API response
     * @return array
     */
    public function getResponse() : array;

    /**
     * Gets the API request headers
     * @return array
     */
    public function getHeaders() : array;

    /**
     * Gets the API request body
     * @return array
     */
    public function getBody() : array;

    /**
     * Gets the API request method
     * @return string
     */
    public function getMethod() : string;
}

