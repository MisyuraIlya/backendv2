<?php

namespace App\Enum;

enum DocumentsType: string
{
    case ALL = 'all';
    case ORDERS = 'order';
    case PRICE_OFFER = 'price offer';
    case DELIVERY_ORDER = ' delivery order';
    case AI_INVOICE = 'ai invoice';
    case CI_INVOICE = 'ci invoice';
    case RETURN_ORDERS = 'return order';

    case HISTORY = 'history';

    case DRAFT = 'draft';

    public static function getAllDetails(): array
    {
        return [
            'ALL' => [
                'HEBREW' => self::ALL,
                'ENGLISH' => 'all'
            ],
            'ORDERS' => [
                'HEBREW' => self::ORDERS,
                'ENGLISH' => 'orders'
            ],
            'PRICE_OFFER' => [
                'HEBREW' => self::PRICE_OFFER,
                'ENGLISH' => 'priceOffer'
            ],
            'DELIVERY_ORDER' => [
                'HEBREW' => self::DELIVERY_ORDER,
                'ENGLISH' => 'deliveryOrder'
            ],
            'AI_INVOICE' => [
                'HEBREW' => self::AI_INVOICE,
                'ENGLISH' => 'aiInvoice'
            ],
            'CI_INVOICE' => [
                'HEBREW' => self::CI_INVOICE,
                'ENGLISH' => 'ciInvoice'
            ],
            'RETURN_ORDERS' => [
                'HEBREW' => self::RETURN_ORDERS,
                'ENGLISH' => 'returnOrders'
            ]
        ];
    }

}
