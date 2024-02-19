<?php

namespace App\Erp\Custom;

use App\Erp\Core\ErpManager;

class CustomMethods
{
    public function __construct(
        private readonly ErpManager $erpManager,
    )
    {
    }

    public function GetOnlineProdImages()
    {
//        $this->erpManager->GetRequest('')
        return 'here';
    }
}