<?php

namespace App\ApiResource\Dto;

use App\Entity\Product;
use App\Enum\DocumentTypeHistory;

class CartsDto
{
    /** @var CartItemDto[] */
    public array $cart = [];

}