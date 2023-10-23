<?php

namespace App\Entity;

use App\Repository\ParcelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcelRepository::class)]
class Parcel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?int $length = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $waybill = null;

    #[ORM\ManyToOne(inversedBy: 'parcels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Package $package = null;

    #[ORM\Column]
    private ?int $weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWaybill(): ?string
    {
        return $this->waybill;
    }

    public function setWaybill(?string $waybill): static
    {
        $this->waybill = $waybill;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): static
    {
        $this->package = $package;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
