<?php

namespace App\Cron\Core;

use App\Erp\Core\ErpManager;
use App\Repository\AttributeMainRepository;

class GetMainAttributes
{
    public function __construct(
        private readonly AttributeMainRepository $attributeMainRepository,
        private readonly ErpManager $erpManager,
    )
    {
    }

    public function sync()
    {

    }
}