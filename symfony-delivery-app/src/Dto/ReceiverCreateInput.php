<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ReceiverCreateInput
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $companyName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $contactPerson = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $address = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $city = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[Assert\Length(2)]
    public ?string $country = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 10)]
    public ?string $postCode = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $phoneNumber = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public ?string $emailAddress = null;
}
