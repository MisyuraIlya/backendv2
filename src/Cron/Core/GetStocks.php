<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\ProductRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetStocks
{

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private ProductRepository $productRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    public function sync()
    {

        try {
            $res = (new ErpManager($this->httpClient))->GetStocks();
            foreach ($res->stocks as $itemRec){
                $product = $this->productRepository->findOneBySku($itemRec->sku);
                if($product){
                    $product->setStock($itemRec->stock);
                    $this->productRepository->createProduct($product, true);

                }
            }
        } catch (\Exception $e) {
            $error = new Error();
            $error->setFunctionName('cron get stocks');
            $error->setDescription($e->getMessage());
            $this->errorRepository->createError($error, true);
        }
    }
}