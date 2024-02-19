<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Erp\Core\ErpManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CartessetProvider implements ProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly RequestStack $requestStack,
    )
    {
        $this->fromDate = $this->requestStack->getCurrentRequest()->query->get('from');
        $this->toDate = $this->requestStack->getCurrentRequest()->query->get('to');
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->GetHandler($operation,$uriVariables,$context);
    }


    private function GetHandler($operation,$uriVariables,$context)
    {
        $format = "Y-m-d";
        $dateFrom = \DateTimeImmutable::createFromFormat($format, $this->fromDate);
        $dateTo = \DateTimeImmutable::createFromFormat($format, $this->toDate);

        $response = (new ErpManager($this->httpClient))->GetCartesset(
            $uriVariables['userExtId'],
            $dateFrom,
            $dateTo,
        );

        return $response;
    }
}
