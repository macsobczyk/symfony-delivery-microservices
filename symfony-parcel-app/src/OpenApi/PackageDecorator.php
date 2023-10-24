<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;

final class PackageDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {}

    private array $userSelfUpdateExampleDefinition = [
        'type' => 'object',
        'properties' => [
            'receiver' => [
                'type' => 'array',
                'example' => [
                    'companyName' => 'Swiss National Museum',
                    'contactPerson' => 'John Doe',
                    'address' => 'Museumstrasse 2',
                    'city' => 'Zurich',
                    'postCode' => '8001',
                    'country' => 'CH',
                    'phoneNumber' => '+41442186511',
                    'emailAddress' => 'maciej@sentica.pl'
                ],
            ],
            'parcel' => [
                'type' => 'array',
                'example' => [
                    'weight' => 10,
                    'length' => 60,
                    'width' => 40,
                    'height' => 30,
                ]
            ]
        ],
    ];

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Package.PackageCreateInput.jsonld'] = new \ArrayObject($this->userSelfUpdateExampleDefinition);

        return $openApi;
    }
}
