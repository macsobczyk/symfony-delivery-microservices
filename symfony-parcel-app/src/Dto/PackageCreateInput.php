<?php

namespace App\Dto;

use App\Dto\ParcelCreateInput;
use App\Dto\ReceiverCreateInput;
use Symfony\Component\Validator\Constraints as Assert;

final class PackageCreateInput
{
    #[Assert\NotBlank]
    #[Assert\Valid]
    public ?ReceiverCreateInput $receiver = null;

    #[Assert\NotBlank]
    #[Assert\Valid]
    public ?ParcelCreateInput $parcel = null;

}
