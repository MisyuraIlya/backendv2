<?php

namespace App\Enum;

enum DocumentsType: string
{
    case ALL = 'all';
    case ORDERS = 'order';
    case PRICE_OFFER = 'priceOffer';
    case DELIVERY_ORDER = ' deliveryOrder';
    case AI_INVOICE = 'aiInvoice';
    case CI_INVOICE = 'ciInvoice';
    case RETURN_ORDERS = 'returnOrder';

    case HISTORY = 'history';

    case DRAFT = 'draft';

    public static function getAllDetails(): array
    {
        return [
            'all' => [
                'HEBREW' => self::ALL,
                'ENGLISH' => self::ALL
            ],
            'order' => [
                'HEBREW' => self::ORDERS,
                'ENGLISH' => self::ORDERS
            ],
            'priceOffer' => [
                'HEBREW' => self::PRICE_OFFER,
                'ENGLISH' => self::PRICE_OFFER
            ],
            'deliveryOrder' => [
                'HEBREW' => 'deliveryOrder',
                'ENGLISH' => self::DELIVERY_ORDER
            ],
            'aiInvoice' => [
                'HEBREW' => self::AI_INVOICE,
                'ENGLISH' => self::AI_INVOICE,
            ],
            'ciInvoice' => [
                'HEBREW' => self::CI_INVOICE,
                'ENGLISH' => self::CI_INVOICE,
            ],
            'returnOrder' => [
                'HEBREW' => self::RETURN_ORDERS,
                'ENGLISH' => self::RETURN_ORDERS,
            ],
            'history' => [
                'HEBREW' => self::HISTORY,
                'ENGLISH' => self::HISTORY,
            ],
            'draft' => [
                'HEBREW' => self::DRAFT,
                'ENGLISH' => self::DRAFT,
            ]
        ];
    }

}
