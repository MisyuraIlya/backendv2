<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Dto\CartItemDto;
use App\ApiResource\Dto\CartsDto;
use App\Entity\HistoryDetailed;
use App\Entity\User;
use App\State\SendOrderProcessor;
use App\Enum\DocumentsType;
#[ApiResource(
    shortName: 'SendOrder',
    operations: [
        new Post(
            processor: SendOrderProcessor::class
        ),
    ],
)]
class SendOrder
{
    public ?int $id = null;

    public ?string $userExtId = null;

    public ?Array $user;

    public ?float $total = null;

    public ?float $totalBeforeTax = null;

    public ?DocumentsType $documentType = null;

    public ?int $agentId = null;

    public ?bool $isBuyByCreditCard = null;

    public ?int $discount = null;

    public ?int $deliveryPrice = null;

    public ?\DateTimeImmutable $deliveryDate = null;

    public ?\DateTimeImmutable $createdAt = null;

    public ?string $comment = null;

    /**
     * @var CartItemDto[]>
     */
    public array $products = [];

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getUserExtId(): ?string
    {
        return $this->userExtId;
    }

    /**
     * @param string|null $userExtId
     */
    public function setUserExtId(?string $userExtId): void
    {
        $this->userExtId = $userExtId;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void
    {
        $this->total = $total;
    }

    /**
     * @return float|null
     */
    public function getTotalBeforeTax(): ?float
    {
        return $this->totalBeforeTax;
    }

    /**
     * @param float|null $totalBeforeTax
     */
    public function setTotalBeforeTax(?float $totalBeforeTax): void
    {
        $this->totalBeforeTax = $totalBeforeTax;
    }

    /**
     * @return DocumentsType|null
     */
    public function getDocumentType(): ?DocumentsType
    {
        return $this->documentType;
    }

    /**
     * @param DocumentsType|null $documentType
     */
    public function setDocumentType(?DocumentsType $documentType): void
    {
        $this->documentType = $documentType;
    }

    /**
     * @return int|null
     */
    public function getAgent(): ?int
    {
        return $this->agentId;
    }

    /**
     * @param int|null $agentId
     */
    public function setAgent(?int $agentId): void
    {
        $this->agentId = $agentId;
    }

    /**
     * @return bool|null
     */
    public function getIsBuyByCreditCard(): ?bool
    {
        return $this->isBuyByCreditCard;
    }

    /**
     * @param bool|null $isBuyByCreditCard
     */
    public function setIsBuyByCreditCard(?bool $isBuyByCreditCard): void
    {
        $this->isBuyByCreditCard = $isBuyByCreditCard;
    }

    /**
     * @return int|null
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param int|null $discount
     */
    public function setDiscount(?int $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return int|null
     */
    public function getDeliveryPrice(): ?int
    {
        return $this->deliveryPrice;
    }

    /**
     * @param int|null $deliveryPrice
     */
    public function setDeliveryPrice(?int $deliveryPrice): void
    {
        $this->deliveryPrice = $deliveryPrice;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDeliveryDate(): ?\DateTimeImmutable
    {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTimeImmutable|null $deliveryDate
     */
    public function setDeliveryDate(?\DateTimeImmutable $deliveryDate): void
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    #[ApiProperty(identifier: true)]
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }





}