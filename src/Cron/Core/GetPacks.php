<?php

namespace App\Cron\Core;

use App\Entity\PackMain;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\PackMainRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetPacks
{

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly PackMainRepository $packMainRepository,
        private readonly ErrorRepository $errorRepository
    )
    {
    }

    public function sync()
    {
        $response = (new ErpManager($this->httpClient, $this->errorRepository))->GetPackMain();
//        dd($response);
        foreach ($response->packs as $itemRec){
            $pack = $this->packMainRepository->findOneByExtIdAndQuantity($itemRec->extId, $itemRec->quantity);
            if(!$pack){
                $pack = new PackMain();
                $pack->setExtId($itemRec->extId);
            }
            $pack->setName($itemRec->name);
            $pack->setQuantity($itemRec->quantity);
            $pack->setBarcode($itemRec->barcode);
            $this->packMainRepository->save($pack, true);
        }
    }
}