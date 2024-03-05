<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Entity\History;
use App\Enum\DocumentsType;
use App\Enum\DocumentTypeHistory;
use App\Erp\Core\Dto\DocumentDto;
use App\Erp\Core\Dto\DocumentsDto;
use App\Erp\Core\ErpManager;
use App\Repository\HistoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class DocumentsProvider implements ProviderInterface
{
    private $userPriceLists = [];
    public function __construct(
        private readonly RequestStack $requestStack,
        private Pagination $pagination,
        private readonly ProductRepository $productRepository,
        private readonly UserRepository $userRepository,
        private readonly ErpManager $erpManager,
        private readonly HistoryRepository $historyRepository,
    )
    {

        $this->documentType = $this->requestStack->getCurrentRequest()->attributes->get('documentType');
        $this->fromDate= $this->requestStack->getCurrentRequest()->attributes->get('dateFrom');
        $this->toDate = $this->requestStack->getCurrentRequest()->attributes->get('dateTo');
        $this->userId = $this->requestStack->getCurrentRequest()->query->get('userId');
        $this->userDb = $this->userRepository->findOneById($this->userId);
        $this->limit = $this->requestStack->getCurrentRequest()->query->get('limit');


//        $this->documentItemType = $this->requestStack->getCurrentRequest()->query->get('documentItemType');
//        $this->handleUserPriceLists();



    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $currentPage = $this->pagination->getPage($context);
            $result = $this->CollectionHandler($operation,$uriVariables,$context);
            $totalItems = count($result);
            return new TraversablePaginator(
                new \ArrayIterator($result),
                $currentPage,
                $this->limit,
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
        $page = $this->pagination->getPage($context);
        if($this->documentType == 'all') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::ALL, $page, $this->limit)->documents;
        } elseif($this->documentType == 'orders') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::ORDERS,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'priceOffer') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::PRICE_OFFER,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'deliveryOrder') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::DELIVERY_ORDER,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'aiInvoice') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::AI_INVOICE,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'ciInvoice') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::CI_INVOICE,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'returnOrder') {
            return $this->erpManager->GetDocuments($this->userDb , $dateFrom, $dateTo, DocumentsType::RETURN_ORDERS,  $page, $this->limit)->documents;
        } elseif($this->documentType == 'history') {
            $history = $this->historyRepository->historyHandler($dateFrom,$dateTo,$this->userId,$page,  $this->limit);
            return $this->ConvertHistoryToDocumentsDto($history)->documents;
        } elseif($this->documentType == 'draft') {
            $history = $this->historyRepository->historyHandler($dateFrom,$dateTo,$this->userId,$page,  $this->limit ,DocumentsType::DRAFT);
            return $this->ConvertHistoryToDocumentsDto($history)->documents;
        }
    }

    private function GetHandler($operation,$uriVariables,$context)
    {
        $response = $this->erpManager->GetDocumentsItem($uriVariables['documentNumber'],$this->documentItemType);
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
            $handlePrice = $this->erpManager->GetPricesOnline($makats,$this->userPriceLists,$this->userExId);
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

    private function ConvertHistoryToDocumentsDto(array $histoires): DocumentsDto
    {
        $result = new DocumentsDto();
        $result->documents = [];
        foreach ($histoires as $histoire) {
            assert($histoire instanceof History);
            $obj = new DocumentDto();
            $obj->documentNumber = $histoire->getOrderExtId();
            $obj->documentType = $histoire->getDocumentType();
            $obj->userName = $histoire->getUser()->getName();
            $obj->userExId = $histoire->getUser()->getExtId();
            $obj->status = $histoire->getOrderStatus();
            $obj->createdAt = $histoire->getCreatedAt();
            $obj->updatedAt = $histoire->getUpdatedAt();
            $obj->total = $histoire->getTotal();
            $result->documents[] = $obj;
        }
        return $result;
    }

}
