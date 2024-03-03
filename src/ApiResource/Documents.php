<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\DocumentsType;
use App\State\DocumentsProvider;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiProperty;
use App\State\RestoreCartStateProvider;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ApiPlatform\Metadata\Link;

#[ApiResource(
    shortName: 'Documents',
    operations: [
        new GetCollection(
//            description: '/documents?userExId=41104111&from=2023-02-10&to=2023-03-10&documentType=orders&limit=10',
            uriTemplate: '/documents/{documentType}/{dateFrom}/{dateTo}',
            uriVariables: [
                'documentType' => new Link(fromClass: DocumentsType::class),
                'dateFrom' => new Link(fromClass: Date::class),
                'dateTo' => new Link(fromClass: Date::class),
            ],
        ),
        new Get()
    ],
    paginationItemsPerPage: 10,
    provider: DocumentsProvider::class,
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