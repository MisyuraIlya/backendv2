<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Dto\CartItemDto;
use App\ApiResource\Dto\CartsDto;
use App\Entity\HistoryDetailed;
use App\Enum\DocumentTypeHistory;
use App\State\SendNotificationProcessor;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/notifications/send',
            processor: SendNotificationProcessor::class
        ),
    ],
)]
class SendNotification
{
    public ?int $identifier = null;

    public ?array $values = null;

    public ?int $notificationId = null;

    /**
     * @return int|null
     */
    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    /**
     * @param int|null $identifier
     */
    public function setIdentifier(?int $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return array|null
     */
    public function getValues(): ?array
    {
        return $this->values;
    }

    /**
     * @param array|null $values
     */
    public function setValues(?array $values): void
    {
        $this->values = $values;
    }

    /**
     * @return int|null
     */
    public function getNotificationId(): ?int
    {
        return $this->notificationId;
    }

    /**
     * @param int|null $notificationId
     */
    public function setNotificationId(?int $notificationId): void
    {
        $this->notificationId = $notificationId;
    }



}