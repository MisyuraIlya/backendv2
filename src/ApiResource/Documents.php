<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\DocumentsType;
use App\State\DocumentsProvider;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiProperty;
use App\State\RestoreCartStateProvider;
use phpDocumentor\Reflection\Types\String_;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ApiPlatform\Metadata\Link;

#[ApiResource(
    shortName: 'Documents',
    operations: [
        new GetCollection(
            uriTemplate: '/documents/{documentType}/{dateFrom}/{dateTo}',
            uriVariables: [
                'documentType' => new Link(fromClass: DocumentsType::class),
                'dateFrom' => new Link(fromClass: Date::class),
                'dateTo' => new Link(fromClass: Date::class),
            ],
            provider: DocumentsProvider::class,
        ),
        new Get(
            uriTemplate: '/documentItems/{documentType}/{documentNumber}',
            uriVariables: [
                'documentType' => new Link(fromClass: DocumentsType::class),
                'documentNumber' => new Link(fromClass: String_::class),
            ],
            provider: DocumentsProvider::class,
        )
    ],
    paginationItemsPerPage: 10,
)]

class Documents
{
    public ?string $documentNumber;

    public function __construct(string $documentNumber)
    {
        $this->documentNumber = $documentNumber;
    }

    #[ApiProperty(identifier: true)]
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }
}