<?php

namespace App\Erp\Custom;

use App\Erp\Core\ErpManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CustomMethods
{

    public function __construct(
        private readonly ErpManager $erpManager
    )
    {}

    public function GetOnlineProdImages()
    {
//        $this->erpManager->GetRequest('')
        return 'here';
    }
}