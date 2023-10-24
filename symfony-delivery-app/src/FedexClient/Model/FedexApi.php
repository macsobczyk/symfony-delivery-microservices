<?php

declare(strict_types=1);

namespace App\FedexClient\Model;

use Symfony\Component\HttpFoundation\Request;
use UnhandledMatchError;

enum FedexApi
{
    const SANDBOX_URL = 'https://apis-sandbox.fedex.com';
    const PRODUCTION_URL = 'https://apis.fedex.com';

    case Authorize;
    case TrackByTrackingNumberV1;
    case CreateShipmentV1;
    case ValidateAddressV1;
    case ValidatePostalV1;
    case CreatePickupV1;
    case CheckPickupAvailabilityV1;




    /**
     * Gets the URL for the API call
     *
     * @param bool $prod
     * @param string $version
     * @return string
     *
     * @throws UnhandledMatchError
     */
    public function getUrl(bool $prod) : string
    {
        $url = $prod ? self::PRODUCTION_URL : self::SANDBOX_URL;
        $url .= $this->webservice();
        $url .= $this->version();
        $url .= $this->uri();

        return $url;
    }

    /**
     * Gets the webservice for the endpoint
     *
     * @return string
     * @throws UnhandledMatchError
     */
    public function webservice() : string
    {
        return match($this)
        {
            self::Authorize => '',
            self::TrackByTrackingNumberV1 => '/track',
            self::CreateShipmentV1 => '/ship',
            self::CreatePickupV1 => '/pickup',
            self::ValidatePostalV1 => '/country',
            self::ValidateAddressV1 => '/address',
            self::CheckPickupAvailabilityV1 => '/pickup'
        };
    }

    /**
     * Gets the version of API
     *
     * @return string
     */
    public function version() : string
    {
        return match($this)
        {
            self::Authorize => '',
            default => '/v1',
        };
    }


    /**
     * Gets the URI for the API call
     *
     * @return string
     * @throws UnhandledMatchError
     */
    public function uri() : string
    {
        return match($this)
        {
            self::Authorize => '/oauth/token',
            self::TrackByTrackingNumberV1 => '/trackingnumbers',
            self::CreateShipmentV1 => '/shipments',
            self::CreatePickupV1 => '/pickups',
            self::ValidatePostalV1 => '/postal/validate',
            self::ValidateAddressV1 => '/addresses/resolve',
            self::CheckPickupAvailabilityV1 => '/pickups/availabilities',

        };
    }

    /**
     * Gets the HTTP method for the API call
     *
     * @return string
     * @throws UnhandledMatchError
     */
    public function method(): string
    {
        return match($this)
        {
            default => Request::METHOD_POST,

        };
    }

    /**
     * Gets the headers for the API call
     *
     * @return array
     * @throws UnhandledMatchError
     */
    public function headers() : array
    {
        return match($this)
        {
            self::Authorize => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            default => [
                'Content-Type' => 'application/json'
            ]
        };
    }

    /**
     * Gets the data key for the API call
     *
     * @return string
     * @throws UnhandledMatchError
     */
    public function dataKey() : string
    {
        return match($this)
        {
            self::Authorize => 'access_token',
            self::TrackByTrackingNumberV1 => 'completeTrackResults',
            self::CreateShipmentV1 => 'output',
            self::CreatePickupV1 => 'output',
            self::ValidatePostalV1 => 'output',
            self::ValidateAddressV1 => 'output',
            self::CheckPickupAvailabilityV1 => 'output',
        };
    }
}
