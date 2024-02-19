<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Entity\PriceListDetailed;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\PriceListDetailedRepository;
use App\Repository\PriceListRepository;
use App\Repository\ProductRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetPriceListDetailed
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ProductRepository $productRepository,
        private readonly PriceListRepository $priceListRepository,
        private readonly PriceListDetailedRepository $priceListDetailedRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    public function sync()
    {
        try {
            $response = (new ErpManager($this->httpClient))->GetPriceListDetailed();
            foreach ($response->priceListsDetailed as $itemRec){
                $findPriceList = $this->priceListRepository->findOneByExtId($itemRec->priceList);
                $findProduct = $this->productRepository->findOneBySku($itemRec->sku);
                $priceListDetailed = $this->priceListDetailedRepository->findOneBySkuAndPriceList($itemRec->sku, $itemRec->priceList);
                if(!$priceListDetailed) {
                    $priceListDetailed = new PriceListDetailed();
                    $priceListDetailed->setProduct($findProduct);
                    $priceListDetailed->setPriceList($findPriceList);
                }
                $priceListDetailed->setPrice($itemRec->price);
                $priceListDetailed->setDiscount($itemRec->discount);
                $this->priceListDetailedRepository->createPriceListDetailed($priceListDetailed,true);
            }
        } catch (\Exception $e) {
            $error = new Error();
            $error->setFunctionName('cron get price list  detailed');
            $error->setDescription($e->getMessage());
            $this->errorRepository->createError($error, true);
        }
    }
}