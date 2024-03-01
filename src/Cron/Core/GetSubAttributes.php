<?php

namespace App\Cron\Core;

use App\Entity\ProductAttribute;
use App\Entity\AttributeSub;
use App\Erp\Core\ErpManager;
use App\Repository\AttributeMainRepository;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductRepository;
use App\Repository\AttributeSubRepository;

class GetSubAttributes
{
    public function __construct(
        private readonly AttributeSubRepository     $attributeSubRepository,
        private readonly ProductRepository          $productRepository,
        private readonly AttributeMainRepository    $attributeMainRepository,
        private readonly ProductAttributeRepository $productAttributeRepository,
        private readonly ErpManager                 $erpManager,
    )
    {}

    public function sync()
    {

        $response = $this->erpManager->GetProducts();
        foreach ($response->products as $itemRec) {
            if($itemRec->status) {

                $attributeMain = $this->attributeMainRepository->findOneByExtId(999);
                $subAttribute = $this->attributeSubRepository->findOneByTitle($itemRec->Extra3);
                if(empty($subAttribute) && $itemRec->Extra3){
                    $newSubAt = new AttributeSub();
                    $newSubAt->setTitle($itemRec->Extra3);
                    $newSubAt->setAttribute($attributeMain);
                    $this->attributeSubRepository->createSubAttribute($newSubAt,true);
                }

                $product = $this->productRepository->findOneBySku($itemRec->sku);

                if(!empty($product) && !empty($subAttribute)){
                    $attribute = $this->productAttributeRepository->findOneByProductIdAndAttributeSubId($product->getId(), $subAttribute->getId());

                    if(empty($attribute)){
                        $attribute = new ProductAttribute();
                        $attribute->setProduct($product);
                        $attribute->setAttributeSub($subAttribute);
                    }

                    $this->productAttributeRepository->save($attribute,true);
                }

            }

        }

    }
}