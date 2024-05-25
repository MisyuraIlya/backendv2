<?php

namespace App\Enum;

enum CatalogDocumentTypeEnum: string
{
    case CATALOG = 'catalog';
    case SEARCH = 'search';
    case SPECIAL = 'special';
    case NEW = 'new';
}
