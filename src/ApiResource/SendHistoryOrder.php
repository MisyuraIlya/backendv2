<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\SendHistoryOrderProcessor;

#[ApiResource(
    shortName: 'SendHistoryOrder',
    operations: [
        new Post(
            processor: SendHistoryOrderProcessor::class
        ),
    ],
)]
class SendHistoryOrder
{
    public ?int $historyId = null;

    public ?int $agentApprovedId = null;

    /**
     * @return int|null
     */
    public function getHistoryId(): ?int
    {
        return $this->historyId;
    }

    /**
     * @param int|null $historyId
     */
    public function setHistoryId(?int $historyId): void
    {
        $this->historyId = $historyId;
    }

    /**
     * @return int|null
     */
    public function getAgentApprovedId(): ?int
    {
        return $this->agentApprovedId;
    }

    /**
     * @param int|null $agentApprovedId
     */
    public function setAgentApprovedId(?int $agentApprovedId): void
    {
        $this->agentApprovedId = $agentApprovedId;
    }




}