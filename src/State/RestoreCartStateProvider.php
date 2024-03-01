<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Dto\CartsDto;
use App\ApiResource\RestoreCart;
use App\Entity\User;
use App\Erp\Core\ErpManager;
use App\Repository\HistoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

class RestoreCartStateProvider implements ProviderInterface
{
    public function __construct(
        private readonly HistoryRepository $historyRepository,
        private readonly UserRepository $userRepository,
        private readonly ProductRepository $productRepository,
        private readonly ErpManager $erpManager,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $documentType = $uriVariables['documentType'];
        $priceType = $uriVariables['priceType'];
        $orderNumber = $uriVariables['orderNumber'];
        $userExtId = $uriVariables['userExtId'];
        $table = $uriVariables['table'];
        $user = $this->userRepository->findFirstExtId($userExtId);

        if($documentType === 'history' && !empty($user)) {
            $response = $this->handleHistory($orderNumber,$user, $priceType);

        }

        if($documentType === 'online' && !empty($user)) {
            $response = $this->handleOnline($orderNumber,$user, $priceType,$table);
        }
        return $response->cart;
    }

    private function handleOnline($orderNumber, User $user, $priceType, $table): CartsDto
    {
        $result = new CartsDto();
        $data = $this->erpManager->GetDocumentsItem($orderNumber,$table);
        $skus = [];
        foreach ($data->products as $itemRec){
            $skus[] = $itemRec->sku;
            $product = $this->productRepository->findOneBySku($itemRec->sku);
            if($product && $product->isIsPublished()){
                $obj = new RestoreCart();
                $obj->total = $product->getBasePrice() * $itemRec->quantity;
                $obj->sku = $product->getSku();
                $obj->discount = 0;
                $obj->stock = 99999;
                $obj->price = $product->getBasePrice();
                $obj->quantity = $itemRec->quantity;
                $product->setFinalPrice($product->getBasePrice());
                $obj->product = $product;
                $result->cart[] = $obj;
            }
        }


        if($priceType == 'updatedPrice') {
            $userPriceLists = [];
            foreach ($user->getPriceListUsers() as $itemRec){
                $userPriceLists[] = $itemRec->getPriceList()->getExtId();
            }
            if(!empty($userPriceLists)){
                $prices = $this->erpManager->GetPricesOnline($skus,$userPriceLists,$user->getExtId());
                foreach ($result->cart as $itemRec){
                    foreach ($prices->prices as $priceRec){
                        if($itemRec->sku === $priceRec->sku){
                            $itemRec->price = $priceRec->price;
                            $itemRec->total = $priceRec->price * $itemRec->quantity;
                            $itemRec->getProduct()->setFinalPrice($priceRec->price);
                            if($priceRec->discountPrecent){
                                $itemRec->discount = $priceRec->discountPrecent;
                            } else {
                                $itemRec->discount = 0;
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($data->products as $itemRec) {
                foreach ($result->cart as $cartItem) {
                    if($itemRec->sku === $cartItem->getSku()) {
                        $cartItem->setPrice($itemRec->priceByOne);
                        $cartItem->setDiscount($itemRec->discount);
                        $cartItem->setTotal($itemRec->total);
                        $cartItem->getProduct()->setFinalPrice($itemRec->priceByOne);
                    }
                }
            }
        }


//        $inStockProducts = new CartsDto();

//        $stocks = $this->ErpManager->GetStocksOnline($skus);
//        foreach ($result->cart as $itemRec){
//            foreach ($stocks->stocks as $stockRec){
//                if($itemRec->sku === $stockRec->sku){
//                    if($stockRec->stock > 0){
//                        $itemRec->stock = $stockRec->stock;
//                        $inStockProducts->cart[] = $itemRec;
//
//                    }
//                }
//            }
//        }

        return $result;
    }

    private function handleHistory($orderNumber, User $user, $priceType): CartsDto
    {
        $result = new CartsDto();
        $data = $this->historyRepository->findOneById($orderNumber);

        $skus = [];
        foreach ($data->getHistoryDetaileds() as $itemRec){
            $skus[] = $itemRec->getProduct()->getSku();
            $product = $this->productRepository->findOneBySku($itemRec->getProduct()->getSku());
            if($product && $product->isIsPublished()){
                $obj = new RestoreCart();
                $obj->total = $product->getBasePrice() * $itemRec->getQuantity();
                $obj->sku = $product->getSku();
                $obj->discount = 0;
                $obj->stock = 0;
                $obj->price = $product->getBasePrice();
                $obj->quantity = $itemRec->getQuantity();
                $product->setFinalPrice($product->getBasePrice());
                $obj->product = $product;
                $result->cart[] = $obj;
            }

        }

        if($priceType == 'updatedPrice') {
            $priceLists = [];
            foreach ($user->getPriceListUsers() as $itemRec){
                $priceLists[] = $itemRec->getPriceList()->getExtId();
            }
            if($priceLists){
                $prices = $this->erpManager->GetPricesOnline($skus, $priceLists, $user->getExtId());
                foreach ($result->cart as $itemRec){
                    foreach ($prices->prices as $priceRec){
                        if($itemRec->getSku() === $priceRec->sku){
                            $itemRec->setPrice($priceRec->price);
                            $itemRec->getProduct()->setFinalPrice($priceRec->price);
                            if($priceRec->discountPrecent){
                                $itemRec->setDiscount($priceRec->discountPrecent);
                            } else {
                                $itemRec->setDiscount(0);
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($data->getHistoryDetaileds() as $itemRec) {
                foreach ($result->cart as $cartItem) {
                    if($itemRec->getProduct()->getSku() === $cartItem->getSku()) {
                        $cartItem->setPrice($itemRec->getSinglePrice());
                        $cartItem->setDiscount($itemRec->getDiscount());
                        $cartItem->setTotal($itemRec->getTotal());
                        $cartItem->getProduct()->setFinalPrice($itemRec->getSinglePrice());
                    }
                }
            }
        }


        $inStockProducts = new CartsDto();
        $stocks = $this->erpManager->GetStocksOnline($skus);
        $newStocks = $this->erpManager->GetStocksOnline($skus);
        foreach ($result->cart as $itemRec){
            foreach ($newStocks->stocks as $stockRec){
                if($itemRec->sku === $stockRec->sku){
//                    if($stockRec->stock > 0){
                        $itemRec->setStock($stockRec->stock);
                        $inStockProducts->cart[] = $itemRec;
//                    }
                }
            }
        }
        return $inStockProducts ;

    }


}
