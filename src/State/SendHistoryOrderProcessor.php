<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\SendHistoryOrder;
use App\Entity\History;
use App\Entity\User;
use App\Enum\PurchaseStatus;
use App\Repository\HistoryDetailedRepository;
use App\Repository\HistoryRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SendHistoryOrderProcessor implements ProcessorInterface
{

    public function __construct(
        private HistoryRepository $historyRepository,
        private HistoryDetailedRepository $historyDetailedRepository,
        private UserRepository $userRepository,
        private HttpClientInterface $httpClient,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // Handle the state
        assert($data instanceof SendHistoryOrder);
        $findHistory = $this->historyRepository->findOneById($data->getHistoryId());
        $findAgent = $this->userRepository->findOneById($data->getAgentApprovedId());
        if(!$findAgent) throw new \Exception('לא נמצא סוכן');
        if(!$findHistory) throw new \Exception('לא נמצא הזמנה כזאת');
//        $orderNumber = (new ErpManager($this->httpClient))->SendOrder($data->getHistoryId(), $this->historyRepository, $this->historyDetailedRepository);
        $orderNumber = '123123';
        $this->SaveOrderNumber($orderNumber, $findHistory, $findAgent);

    }

    private function SaveOrderNumber(string $orderNumber, History $history, User $agent)
    {
        $history->setOrderExtId($orderNumber);
        $history->setAgentApproved($agent);
        $history->setIsSendErp(true);
        $history->setSendErpAt(new \DateTimeImmutable());
        $history->setOrderStatus(PurchaseStatus::PAID);
        $history->setUpdatedAt(new \DateTimeImmutable());
        $this->historyRepository->save($history, true);
    }
}
