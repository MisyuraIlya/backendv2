<?php

namespace App\Enum;

enum DocumentsType: string
{
    case ALL = 'הכל';
    case ORDERS = 'הזמנות';
    case PRICE_OFFER = 'הצעות מחיר';
    case DELIVERY_ORDER = 'תעודות משלוח';
    case AI_INVOICE = 'חשבוניות מס';
    case CI_INVOICE = 'חשבוניות מס מרכזות';
    case RETURN_ORDERS = 'החזרות';

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
