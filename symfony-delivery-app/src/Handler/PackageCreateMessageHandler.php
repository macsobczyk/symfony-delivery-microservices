<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\ReceiverCreateInput;
use App\FedexClient\DataTransformer\PackageDataTransformer;
use App\FedexClient\DataTransformer\FedexShipmentResponseDataTransformer;
use App\FedexClient\Service as FedexServices;
use App\Message\PackageCreateMessage;
use App\Message\PackageSendMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class PackageCreateMessageHandler
{

    public function __construct(
        private readonly PackageDataTransformer $packageDataTransformer,
        private readonly FedexShipmentResponseDataTransformer $fedexShipmentResponseDataTransformer,
        private readonly FedexServices\Authorize $authorize,
        private readonly FedexServices\CreateShipmentV1 $createShipmentV1,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(PackageCreateMessage $message): void
    {

        $message->requestInput->sender = $this->createSenderInput();

        $fedexResponse = ($this->createShipmentV1)(
            $this->createFedexRequestHeaders(),
            $this->packageDataTransformer->transform($message->requestInput)
        );

        $packageUpdateInput = $this->fedexShipmentResponseDataTransformer->transform($fedexResponse);
        $packageUpdateInput->id = $message->requestInput->id;

        $this->messageBus->dispatch(
            message: new PackageSendMessage($packageUpdateInput)
        );
    }

    /**
     * Creates Fedex request headers
     *
     * @return array
     */
    protected function createFedexRequestHeaders() : array
    {
        return [
            'authorization' => 'Bearer '.($this->authorize)(),
            'Content-Type' => 'application/json',
            'x-locale' => 'pl_PL',
        ];
    }

    /**
     * Creates sender input
     *
     * @return ReceiverCreateInput
     */
    protected function createSenderInput() : ReceiverCreateInput
    {
        $sender = new ReceiverCreateInput();
        $sender->companyName = 'Sentica Sp. z o.o.';
        $sender->contactPerson = 'Maciej Sobczyk';
        $sender->address = 'Basztowa 1';
        $sender->city = 'Gliwice';
        $sender->country = 'PL';
        $sender->postCode = '44100';
        $sender->phoneNumber = '+48555666777';
        $sender->emailAddress = 'maciej@sentica.pl';

        return $sender;
    }
}
