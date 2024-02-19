<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Erp\Core\ErpManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PurchaseHistoryProvider implements ProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $userExtId = $uriVariables['userExtId'];
        $sku = $uriVariables['sku'];
        $ErpManager = (new ErpManager($this->httpClient))->PurchaseHistoryByUserAndSku($userExtId,$sku)->items;
        return $ErpManager;
    }
}
