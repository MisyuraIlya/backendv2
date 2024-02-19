<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Entity\ProductAttribute;
use App\Entity\SubAttribute;
use App\Erp\Core\ErpManager;
use App\Repository\AttributeMainRepository;
use App\Repository\ErrorRepository;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductRepository;
use App\Repository\SubAttributeRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetSubAttributes
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly SubAttributeRepository $subAttributeRepository,
        private readonly ProductRepository $productRepository,
        private readonly AttributeMainRepository $attributeMainRepository,
        private readonly ErrorRepository $errorRepository,
        private readonly ProductAttributeRepository $productAttributeRepository,
    )
    {}

    public function sync()
    {

        // try {
        $response = (new ErpManager($this->httpClient))->GetProducts();
        foreach ($response->products as $itemRec) {
            if($itemRec->status) {

                $attributeMain = $this->attributeMainRepository->findOneByExtId(999);
                $subAttribute = $this->subAttributeRepository->findOneByTitle($itemRec->Extra3);
                if(empty($subAttribute) && $itemRec->Extra3){
                    $newSubAt = new SubAttribute();
                    $newSubAt->setTitle($itemRec->Extra3);
                    $newSubAt->setAttribute($attributeMain);
                    $this->subAttributeRepository->createSubAttribute($newSubAt,true);
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
        // } catch (\Exception $e) {
        //     $error = new Error();
        //     $error->setFunctionName('cron get sub attributes');
        //     $error->setDescription($e->getMessage());
        //     $this->errorRepository->createError($error, true);
        // }

    }
}