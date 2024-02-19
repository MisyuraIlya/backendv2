<?php

namespace App\Entity;

use App\Repository\PriceListUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceListUserRepository::class)]
class PriceListUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'priceListUsers')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'priceListUsers')]
    private ?PriceList $priceList = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiredAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $userId): static
    {
        $this->user = $userId;

        return $this;
    }

    public function getPriceListId(): ?PriceList
    {
        return $this->priceList;
    }

    public function setPriceListId(?PriceList $priceListId): static
    {
        $this->priceList = $priceListId;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeImmutable $expiredAt): static
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }
}
