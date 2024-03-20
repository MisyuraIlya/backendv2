<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\State\Pagination\Pagination;

class AgentClientsProvider implements ProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private Pagination $pagination,
        private UserRepository $userRepository,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $data = $this->CollectionHandler($operation,$uriVariables,$context);
            return new TraversablePaginator(
                new \ArrayIterator($data->getIterator()),
                $data->getCurrentPage(),
                $data->getItemsPerPage(),
                $data->getTotalItems()
            );
        }
        return $this->GetHandler($operation,$uriVariables,$context);
    }

    private function CollectionHandler($operation,$uriVariables,$context): Paginator
    {
        $agentId = (string) $this->requestStack->getCurrentRequest()->attributes->get('agentId');
        $currentPage = $this->pagination->getPage($context);
        $search = (string)  $this->requestStack->getCurrentRequest()->get('search', null);
        return $this->userRepository->GetAgentClients($agentId,$currentPage,50,$search);

    }
}
