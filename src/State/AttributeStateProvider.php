<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Erp\Core\ErpManager;
use App\Repository\AttributeMainRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class AttributeStateProvider implements ProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AttributeMainRepository $attributeMainRepository,
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        private readonly UserRepository $userRepository,
        private readonly ErpManager $erpManager,
    )
    {
        $this->isOnlineMigvan = $_ENV['IS_ONLINE_MIGVAN'] === "true";
        $this->isUsedMigvan = $_ENV['IS_USED_MIGVAN'] === "true";
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
            $userExtId = $this->requestStack->getCurrentRequest()->get('userExtId');
            $search = $this->requestStack->getCurrentRequest()->get('search');

            $migvanOnline = [];
            if($this->isUsedMigvan && $userExtId) {
                if($this->isOnlineMigvan) {
                    $migvanOnline = $this->erpManager->GetMigvanOnline($userExtId)->migvans;
                    if(count($migvanOnline) == 0) {
                        $userExtId = null;
                    }
                } else {
                    $isUserHaveMigvan = $this->userRepository->isUserHaveMigvan($userExtId);
                    if(!$isUserHaveMigvan){
                        $userExtId = null;
                    }
                }
            } else {
                $userExtId = null;
            }

            $lvl1 = $this->requestStack->getCurrentRequest()->attributes->get('lvl1');
            $lvl2 = $this->requestStack->getCurrentRequest()->attributes->get('lvl2');
            $lvl3 = $this->requestStack->getCurrentRequest()->attributes->get('lvl3');
            $response = $this->attributeMainRepository->findAttributesByCategoryExistProducts($lvl1,$lvl2,$lvl3, $userExtId, $migvanOnline,$search);
            return $response;


    }
}
