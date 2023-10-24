<?php

namespace App\FedexClient\Exception;

use App\CourierApi\Exception\ExceptionMessageParser\ExceptionMessageFactory;
use App\CourierApi\Exception\DataAnonymizer\DataAnonymizer;
use Exception;
use Stringable;
use Throwable;

/**
 * Defines a custom exception class
 */
class CourierApiException extends Exception
{

    public function __construct(
        protected $message,
        protected $code,
        private mixed $requestData = null,
        private ?string $uri = null,
        private ?string $methodName = null,
        private ?Throwable $previous = null,
    )
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );

    }
}


