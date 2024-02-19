<?php

namespace App\Cron\Core;

use App\Entity\PackProducts;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\PackMainRepository;
use App\Repository\PackProductsRepository;
use App\Repository\ProductRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetProductPacks
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly PackMainRepository $packMainRepository,
        private readonly PackProductsRepository $packProductsRepository,
        private readonly ErrorRepository $errorRepository,
        private readonly ProductRepository $productRepository,
    )
    {
    }

    public function sync()
    {
        $response = (new ErpManager($this->httpClient, $this->errorRepository))->GetPackProducts();

        foreach ($response->packs as $itemRec){
            $findProduct = $this->productRepository->findOneBySku($itemRec->sku);
            $findMainPack = $this->packMainRepository->findOneByExtIdAndQuantity($itemRec->packExtId, $itemRec->quantity);
            if(!empty($findMainPack) && !empty($findProduct)) {
                $PackProd = $this->packProductsRepository->findOneByProductIdAndPackId($findProduct->getId(), $findMainPack->getId());
                if(empty($PackProd)){
                    $PackProd = new PackProducts();
                    $PackProd->setPack($findMainPack);
                    $PackProd->setProduct($findProduct);
                    $this->packProductsRepository->save($PackProd,true);
                }
            }
        }
    }
}