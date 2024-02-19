<?php

namespace App\Cron\Core;

use App\Entity\PackProducts;
use App\Erp\Core\ErpManager;
use App\Repository\PackMainRepository;
use App\Repository\PackProductsRepository;
use App\Repository\ProductRepository;

class GetProductPacks
{
    public function __construct(
        private readonly PackMainRepository $packMainRepository,
        private readonly PackProductsRepository $packProductsRepository,
        private readonly ProductRepository $productRepository,
        private readonly ErpManager $erpManager,
    )
    {
    }

    public function sync()
    {
        $response = $this->erpManager->GetPackProducts();

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