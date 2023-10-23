<?php

declare(strict_types=1);

namespace App\Message;

use App\Dto\PackageCreateInput;

class PackageCreate
{
    public function __construct(
        protected PackageCreateInput $requestInput,
    ){}

    public function getRequestInput(): string
    {
        return $this->requestInput;
    }
}
