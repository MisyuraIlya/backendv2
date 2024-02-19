<?php

namespace App\Enum;

enum DocumentTypeHistory: string
{
    case ORDER = 'order';
    case QUOTE = 'quote';
    case RETURN = 'return';
    case DRAFT = 'draft';
}
