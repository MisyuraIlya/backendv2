<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\CartessetProvider;

#[ApiResource(
    shortName: 'Cartesset',
    operations: [
        new Get()
    ],
    provider: CartessetProvider::class
)]
class Cartesset
{
    public ?string $userExtId;

    public function __construct(string $userExtId)
    {
        $this->userExtId = $userExtId;
    }

    #[ApiProperty(identifier: true)]
    public function getUserExtId(): string
    {
        return $this->userExtId;
    }
}