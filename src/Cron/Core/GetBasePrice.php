<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\ProductRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetBasePrice
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
            $endpoint = "/PRICELIST";
            $queryParameters = [
                '$filter' => "PLNAME eq 'קB2B'",
                '$expand' => 'PARTPRICE2_SUBFORM',
            ];
            $queryString = http_build_query($queryParameters);
            $urlQuery = $endpoint . '?' . $queryString;

            $res = (new ErpManager($this->httpClient,$this->errorRepository))->GetRequest($urlQuery);

            if($res) {
                foreach ($res as $itemRec) {
                    foreach ($itemRec['PARTPRICE2_SUBFORM'] as $subRec) {
                        $product = $this->productRepository->findOneBySku($subRec['PARTNAME']);
                        if($product) {
                            $product->setBasePrice($subRec['PRICE']);
                            $this->productRepository->createProduct($product, true);
                        }

                    }
                }
            }
        } catch (\Exception $e) {
            $error = new Error();
            $error->setFunctionName('cron get prices');
            $error->setDescription($e->getMessage());
            $this->errorRepository->createError($error, true);
        }
    }
}