<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Entity\PriceList;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\PriceListRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetPriceList
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly PriceListRepository $priceListRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    public function sync()
    {
        try {
            $response = (new ErpManager($this->httpClient,$this->errorRepository))->GetPriceList();
            foreach ($response->priceLists as $itemRec){
                $priceList = $this->priceListRepository->findOneByExtId($itemRec->priceListExtId);
                if(!$priceList){
                    $priceList = new PriceList();
                    $priceList->setExtId($itemRec->priceListExtId);
                }
                $priceList->setTitle($itemRec->priceListTitle);
                $this->priceListRepository->createPriceList($priceList,true);
            }
        } catch (\Exception $e) {
            $error = new Error();
            $error->setFunctionName('cron price list');
            $error->setDescription($e->getMessage());
            $this->errorRepository->createError($error, true);
        }

    }
}