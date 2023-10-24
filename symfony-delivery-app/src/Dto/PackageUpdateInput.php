<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class PackageUpdateInput
{
    #[Assert\NotBlank]
    public ?int $id = null;

    #[Assert\NotBlank]
    public ?string $transactionId = null;

    #[Assert\NotBlank]
    public ?string $trackingNumber = null;

}
