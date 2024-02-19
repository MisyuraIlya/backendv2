<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Entity\Product;
use App\Erp\Core\ErpManager;
use App\Repository\CategoryRepository;
use App\Repository\ErrorRepository;
use App\Repository\ProductRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetProducts
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    public function sync()
    {
        $skip = 0;
        $pageSize = 30;
        do {
            $res = (new ErpManager($this->httpClient,$this->errorRepository))->GetProducts($pageSize, $skip);
            if (!empty($res->products)) {
                foreach ($res->products as $key => $itemRec) {
                    $product = $this->productRepository->findOneBySku($itemRec->sku);
                    if (!$product) {
                        $product = new Product();
                        $product->setSku($itemRec->sku);
                        $product->setCreatedAt(new \DateTimeImmutable());
                    }
                    $product->setOrden($key);
                    $product->setTitle($itemRec->title);
                    $product->setPackQuantity($itemRec->packQuantity);
                    $product->setBasePrice($itemRec->baseprice);
                    $product->setUpdatedAt(new \DateTimeImmutable());
                    $product->setIsPublished($itemRec->status);
                    $product->setExtLvl2($itemRec->categoryLvl2Id);
                    $product->setExtLvl3($itemRec->categoryLvl3Id);
                    $product->setIsHumane($itemRec->isHumane);
                    $product->setIsVeterinary($itemRec->isVetrinary);
                    $product->setIsPharmecies($itemRec->isPharamecies);
                    $product->setIsMedicalCenter($itemRec->isMedicalCenter);
                    $product->setIsHospital($itemRec->isHospital);
                    $product->setLink($itemRec->link);
                    $product->setLinkTitle($itemRec->linkTitle);
                    $product->setInnerHtml($itemRec->innerHtml);
                    $product->setIsDrugNotInBasket($itemRec->isDrugNotInBasket);
                    $this->productRepository->createProduct($product, true);
                }
                $skip += $pageSize;
            } else {
                break;
            }
        } while (true);
    }

}