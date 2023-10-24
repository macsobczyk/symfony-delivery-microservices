<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ParcelCreateInput
{
    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $length = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $width = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 30, max: 60)]
    public ?int $height = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 23)]
    public ?int $weight = null;

}