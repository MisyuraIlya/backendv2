<?php

namespace App\Cron\Core;

use App\Entity\Category;
use App\Enum\CategoryEnum;
use App\Erp\Core\ErpManager;
use App\Repository\CategoryRepository;

class GetCategories
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ErpManager $erpManager,
    )
    {
    }

    public function sync()
    {

    }
}