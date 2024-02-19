<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Erp\Core\ErpManager;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DocumentsProvider implements ProviderInterface
{
    private $userPriceLists = [];
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly RequestStack $requestStack,
        private Pagination $pagination,
        private readonly ProductRepository $productRepository,
        private readonly UserRepository $userRepository,
    )
    {
        $this->userExId = $this->requestStack->getCurrentRequest()->query->get('userExId');
        $this->fromDate = $this->requestStack->getCurrentRequest()->query->get('from');
        $this->userDb = $this->userRepository->findFirstExtId($this->userExId);
        $this->toDate = $this->requestStack->getCurrentRequest()->query->get('to');
        $this->documentType = $this->requestStack->getCurrentRequest()->query->get('documentType');
        $this->documentItemType = $this->requestStack->getCurrentRequest()->query->get('documentItemType');
        $this->limit = $this->requestStack->getCurrentRequest()->query->get('limit');
        $this->handleUserPriceLists();

    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $currentPage = $this->pagination->getPage($context);
            $itemsPerPage = $this->pagination->getLimit($operation, $context);
            $offset = $this->pagination->getOffset($operation, $context);
            $result = $this->CollectionHandler($operation,$uriVariables,$context);
            $totalItems = count($result->documents);
            $start = ($currentPage - 1) * $itemsPerPage;
            $slicedResult = array_slice($result->documents, $start, $itemsPerPage);
            return new TraversablePaginator(
                new \ArrayIterator($slicedResult),
                $currentPage,
                $itemsPerPage,
                $totalItems,
            );
        }
        return $this->GetHandler($operation,$uriVariables,$context);
    }

    private function CollectionHandler($operation,$uriVariables,$context)
    {
        $format = "Y-m-d";
        $dateFrom = \DateTimeImmutable::createFromFormat($format, $this->fromDate);
        $dateTo = \DateTimeImmutable::createFromFormat($format, $this->toDate);

        $response = (new ErpManager($this->httpClient))->GetDocuments(
            $this->userExId,
            $dateFrom,
            $dateTo,
            $this->documentType,
            $this->limit
        );
//        $response->selectBox = DocumentsType::getAllDetails();

        return $response;

    }

    private function GetHandler($operation,$uriVariables,$context)
    {
        $response = (new ErpManager($this->httpClient))->GetDocumentsItem($uriVariables['documentNumber'],$this->documentItemType);
        $makats = [];
        foreach ($response->products as &$itemRec){
            $findProd = $this->productRepository->findOneBySkuAndToArray($itemRec->sku);
            $findProdPacakge = $this->productRepository->findOneBySku($itemRec->sku);

            if(!empty($findProd) && $findProd[0]){
                $makats[] = $findProd[0]['sku'];
                $itemRec->product = $findProd[0];
            }
        }

        try {
            $handlePrice = (new ErpManager($this->httpClient))->GetPricesOnline($makats,$this->userPriceLists,$this->userExId);
            foreach ($handlePrice->prices as $price){
                foreach ($response->products as $subRec){
                    if($price->sku == $subRec->product['sku']){
                        $subRec->product['finalPrice'] = $price->price;
                        $subRec->product['discount'] = $price->discountPrecent;
                    }

                }
            }
        } catch (\Exception $e){
            foreach ($response->products as $itemRecc) {
                $itemRecc->product['finalPrice'] = $itemRecc->priceByOne;

            }
        }


        return $response;
    }

    private function handleUserPriceLists()
    {
        foreach ($this->userDb->getPriceListUsers() as $itemRec){
            $this->userPriceLists[] = $itemRec->getPriceListId()->getExtId();
        }
    }

}
