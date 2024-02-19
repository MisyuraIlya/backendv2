<?php

namespace App\Erp\Core\Priority;

use App\Erp\Core\Dto\PriceDto;
use App\Erp\Core\Dto\PricesDto;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * lvl1 - מחיר מדימרקט -> LOGPART &$expand=PRIO_PRICELISTPART_SUBFORM
 * lvl2 - מחיר מחירון -> PRICELIST?$filter=PLNAME eq '30' or PLNAME eq '29' or PLNAME eq '32' or PLNAME eq '40' or PLNAME eq '852187'
 * &$expand=PARTPRICE2_SUBFORM($select=PARTNAME,PRICE)
 * lvl3 - מחיר בסיס -> LOGPART -> PRICELISTBASE
 */
class PricesLogic
{
    public function __construct(?array $skus, ?array $priceList, string $userExtId, HttpClientInterface $httpClient,string $username, string $password, string $url)
    {
        $this->skus = $skus;
        $this->priceLists = $priceList;
        $this->userExtId = $userExtId;
        $this->uncompletedSkus = [];
        $this->result = new PricesDto();
        $this->imploadedMakats = $this->ImplodeQueryByMakats($skus);
        $this->imploadedPriceLists = $this->ImplodeQueryByPlname($priceList);
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
    }

    public function pricesLvl1()
    {
        $endpoint2 = "/LOGPART";
        $queryParameters2 = [
            '$filter' => $this->imploadedMakats,
            '$select' => 'PARTNAME',
            '$expand' => 'PRIO_PRICELISTPART_SUBFORM($select=PRICE,PERCENT;$filter=CUSTNAME eq '. "'" . $this->userExtId. "'" .')',
        ];
        $queryString2 = http_build_query($queryParameters2);
        $urlQuery2 = $endpoint2 . '?' . $queryString2;
        $response = $this->GetRequest($urlQuery2);
        foreach ($response as $priceRec) {
            if(!empty($priceRec['PRIO_PRICELISTPART_SUBFORM'])) {
                foreach ($priceRec['PRIO_PRICELISTPART_SUBFORM'] as $listRec) {
                    $dto = new PriceDto();
                    $dto->sku = $priceRec['PARTNAME'];
                    $dto->price = $listRec['PRICE'];
                    $dto->discountPrecent = $listRec['PERCENT'];
                    $this->result->prices[] = $dto;
                }
            } else {
                $this->uncompletedSkus[] = $priceRec['PARTNAME'];
            }

        }
        return $this;
    }

    public function pricesLvl2()
    {
        if(!empty($this->uncompletedSkus)){
            $newImplode = $this->ImplodeQueryByMakats($this->uncompletedSkus);
            $endpoint2 = "/PRICELIST";
            $queryParameters2 = [
                '$filter' => $this->imploadedPriceLists,
                '$expand' => 'PARTPRICE2_SUBFORM($select=PARTNAME,QUANT,UNITNAME,DVATPRICE,PRICE,PERCENT,DPRICE,VATPRICE,BASEPRICE;$filter='. $newImplode .')',
            ];
            $queryString2 = http_build_query($queryParameters2);
            $urlQuery2 = $endpoint2 . '?' . $queryString2;
            $response = $this->GetRequest($urlQuery2);
            foreach ($response as $priceRec) {
                foreach ($priceRec['PARTPRICE2_SUBFORM'] as $listRec) {
                    $dto = new PriceDto();
                    $dto->sku = $listRec['PARTNAME'];
                    $dto->basePrice = $listRec['BASEPRICE'];
                    $dto->price = $listRec['PRICE'];
                    $dto->priceAfterDiscount = $listRec['DPRICE'];
                    $dto->vatPrice = $listRec['VATPRICE'];
                    $dto->vatAfterDiscount = $listRec['DVATPRICE'];
                    $dto->discountPrecent = $listRec['PERCENT'];
                    $this->result->prices[] = $dto;
                }
            }
        }

        return $this;
    }

    public function pricesLvl3()
    {


        return $this;
    }

    public function getData():PricesDto
    {
        return $this->result;
    }

    private function ImplodeQueryByPlname(array $priceList)
    {
        $filterParts = [];
        foreach ($priceList as $pricePlname) {
            $filterParts[] = "PLNAME eq '$pricePlname'";
        }

        $filterString = implode(' or ', $filterParts);
        return $filterString;
    }

    private function ImplodeQueryByMakats(array $makats)
    {
        $filterParts = [];
        foreach ($makats as $sku) {
            $filterParts[] = "PARTNAME eq '$sku'";
        }

        $filterString = implode(' or ', $filterParts);
        return $filterString;
    }

    public function GetRequest($query)
    {
        $response = $this->httpClient->request(
            'GET',
            $this->url.$query,
            [
                'auth_basic' => [$this->username, $this->password],
                'http_version' => '1.1',
                'timeout' => 600
            ]
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        return $content['value'];
    }
}