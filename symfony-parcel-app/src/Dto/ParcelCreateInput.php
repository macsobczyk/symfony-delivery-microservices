<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class ParcelCreateInput
{
    #[ApiProperty(
        openapiContext: [
            'type' => 'int',
            'example' => 60
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $length = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'int',
            'example' => 40
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $width = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'int',
            'example' => 30
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $height = null;

    #[ApiProperty(
        openapiContext: [
            'type' => 'int',
            'example' => 10
        ]
    )]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 23)]
    public ?int $weight = null;

}