<?php

namespace App\Cron\Core;

use App\Entity\Error;
use App\Repository\AttributeMainRepository;
use App\Repository\ErrorRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetMainAttributes
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly AttributeMainRepository $attributeMainRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    public function sync()
    {
        try {

        } catch (\Exception $e) {
            $error = new Error();
            $error->setFunctionName('cron get main attributes');
            $error->setDescription($e->getMessage());
            $this->errorRepository->createError($error, true);
        }
    }
}