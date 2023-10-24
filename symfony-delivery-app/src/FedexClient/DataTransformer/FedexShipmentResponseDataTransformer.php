<?php

declare(strict_types=1);

namespace App\FedexClient\DataTransformer;

use App\Dto\PackageUpdateInput;

final class FedexShipmentResponseDataTransformer
{
    /**
     * Creates PackageUpdateInput from Fedex response
     *
     * @param array $fedexResponse
     *
     * @return PackageUpdateInput
     */
    public function transform(array $fedexResponse) : PackageUpdateInput
    {
        $dto = new PackageUpdateInput();
        $dto->trackingNumber = $fedexResponse['output']['transactionShipments'][0]['masterTrackingNumber'];
        $dto->transactionId = $fedexResponse['transactionId'];

        return $dto;
    }
}
