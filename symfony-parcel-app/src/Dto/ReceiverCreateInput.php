<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class ReceiverCreateInput
{

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "Customer Company"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $companyName = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "John Doe"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $contactPerson = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "Basztowa 1"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $address = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "Gliwice"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $city = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "44100"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 10)]
    public ?string $postCode = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "+48555666555"
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $phoneNumber = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => "maciej@sentica.pl"
        ]
    )]
    #[ORM\Column(length: 255, nullable: true)]
    public ?string $emailAddress = null;
}
