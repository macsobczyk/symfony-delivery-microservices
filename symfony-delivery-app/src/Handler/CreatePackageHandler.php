<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\PackageCreate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreatePackageHandler
{

    public function __invoke(PackageCreate $message): void
    {
        var_dump($message);
        die();
    }
}
