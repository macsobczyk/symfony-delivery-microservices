<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Package;
use App\Message\PackageSendMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class PackageSendMessageHandler
{

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(PackageSendMessage $message): void
    {

        $package = $this->entityManager->getRepository(Package::class)->find($message->requestInput->id);
        $package->setTransactionId($message->requestInput->transactionId);
        $package->setStatus(Package::STATUS_SENT);
        $package->getParcels()[0]->setWaybill($message->requestInput->trackingNumber);

        $this->entityManager->flush();

        // $this->messageBus->dispatch(
        //     message: new PackageSendMessage($packageUpdateInput)
        // );
    }

}
