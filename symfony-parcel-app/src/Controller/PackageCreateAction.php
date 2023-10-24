<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Dto\PackageCreateInput;
use App\Entity\Package;
use App\Entity\Parcel;
use App\Entity\Receiver;
use App\Message\PackageCreateMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class PackageCreateAction extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus
    ) {}

    public function __invoke(Request $request): Package
    {
        /** @var PackageCreateInput $requestInput */
        $requestInput = $this->serializer->deserialize($request->getContent(), PackageCreateInput::class, 'json');

        $this->validator->validate($requestInput);

        $receiver = new Receiver();
        $receiver->setCompanyName($requestInput->receiver->companyName);
        $receiver->setContactPerson($requestInput->receiver->contactPerson);
        $receiver->setAddress($requestInput->receiver->address);
        $receiver->setCity($requestInput->receiver->city);
        $receiver->setPostCode($requestInput->receiver->postCode);
        $receiver->setPhoneNumber($requestInput->receiver->phoneNumber);
        $receiver->setEmailAddress($requestInput->receiver->emailAddress);
        $receiver->setCountry($requestInput->receiver->country);

        $parcel = new Parcel();
        $parcel->setWeight($requestInput->parcel->weight);
        $parcel->setLength($requestInput->parcel->length);
        $parcel->setWidth($requestInput->parcel->width);
        $parcel->setHeight($requestInput->parcel->height);

        $package = new Package();
        $package->setReceiver($receiver);
        $package->addParcel($parcel);
        $package->setStatus(Package::STATUS_CREATED);

        $this->entityManager->persist($package);
        $this->entityManager->flush();

        $requestInput->id = $package->getId();

        $this->messageBus->dispatch(
            message: new PackageCreateMessage($requestInput)
        );

        return $package;
    }
}