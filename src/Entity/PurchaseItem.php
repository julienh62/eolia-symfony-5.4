<?php

namespace App\Entity;

use App\Repository\PurchaseItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseItemRepository::class)]
class PurchaseItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    private ?Seance $seance = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Purchase $purchase = null;

    #[ORM\Column(length: 255)]
    private ?string $seanceName = null;

    #[ORM\Column]
    private ?int $seancePrice = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getSeanceName(): ?string
    {
        return $this->seanceName;
    }

    public function setSeanceName(string $seanceName): self
    {
        $this->seanceName = $seanceName;

        return $this;
    }

    public function getSeancePrice(): ?int
    {
        return $this->seancePrice;
    }

    public function setSeancePrice(int $seancePrice): self
    {
        $this->seancePrice = $seancePrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}
