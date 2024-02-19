<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Erp\Core\ErpManager;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoriesDynamicProvider implements ProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        private readonly UserRepository $userRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ErpManager $erpManager,

    )
    {
        $this->isOnlineMigvan = $_ENV['IS_ONLINE_MIGVAN'] === "true";
        $this->isUsedMigvan = $_ENV['IS_USED_MIGVAN'] === "true";
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $lvl1 =  $this->requestStack->getCurrentRequest()->get('lvl1');
        $lvl2 =  $this->requestStack->getCurrentRequest()->get('lvl2');
        $lvl3 =  $this->requestStack->getCurrentRequest()->get('lvl3');
        $response = $this->categoryRepository->DynamicCategories($lvl1,$lvl2,$lvl3);
        return $response;
    }
}
