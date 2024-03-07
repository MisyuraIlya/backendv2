<?php

namespace App\Erp\Core;

use App\Entity\User;
use App\Enum\DocumentsType;
use App\Erp\Core\Dto\CartessetDto;
use App\Erp\Core\Dto\CategoriesDto;
use App\Erp\Core\Dto\DocumentItemsDto;
use App\Erp\Core\Dto\DocumentsDto;
use App\Erp\Core\Dto\MigvansDto;
use App\Erp\Core\Dto\PacksMainDto;
use App\Erp\Core\Dto\PacksProductDto;
use App\Erp\Core\Dto\PriceListsDetailedDto;
use App\Erp\Core\Dto\PriceListsDto;
use App\Erp\Core\Dto\PriceListsUserDto;
use App\Erp\Core\Dto\PricesDto;
use App\Erp\Core\Dto\ProductsDto;
use App\Erp\Core\Dto\PurchaseHistory;
use App\Erp\Core\Dto\StocksDto;
use App\Erp\Core\Dto\UsersDto;
use App\Erp\Core\Priority\Priority;
use App\Repository\HistoryDetailedRepository;
use App\Repository\HistoryRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ErpManager implements ErpInterface
{
    private $erp;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    )
    {
        $erpType =  $_ENV['ERP_TYPE'];
        $username =  $_ENV['ERP_USERNAME'];
        $password =  $_ENV['ERP_PASSWORD'];
        $url = $_ENV['ERP_URL'];
        if ($erpType === 'Priority') {
            $this->erp = new Priority($url, $username, $password, $this->httpClient);
        } elseif ($erpType === 'SAP') {
        } else {
            throw new \Exception("Unsupported ERP type: $erpType");
        }
    }

    public function GetRequest(?string $query)
    {
        return $this->erp->GetRequest($query);
    }

    public function PostRequest(\stdClass $object, string $table)
    {
        return $this->erp->PostRequest($object, $table);
    }

    public function PatchRequest(object $object, string $table)
    {
        return $this->erp->PatchRequest($object, $table);
    }

    public function GetPricesOnline(?array $skus, ?array $priceList, string $userExtId):PricesDto
    {
        return $this->erp->GetPricesOnline($skus, $priceList,$userExtId);
    }
    public function GetStocksOnline(?array $skus):StocksDto
    {
        return $this->erp->GetStocksOnline($skus);
    }

    public function GetOnlineUser(string $userExtId):User
    {
        return $this->erp->GetOnlineUser($userExtId);
    }
    public function SendOrder(int $historyId, HistoryRepository $historyRepository, HistoryDetailedRepository $historyDetailedRepository)
    {
        return $this->erp->SendOrder($historyId,$historyRepository,$historyDetailedRepository);
    }
    public function GetMigvanOnline(string $userExtId): MigvansDto
    {
        return $this->erp->GetMigvanOnline($userExtId);
    }
    public function GetDocuments(?User $user ,\DateTimeImmutable $dateFrom, \DateTimeImmutable $dateTo, DocumentsType $documentType): DocumentsDto
    {

        return $this->erp->GetDocuments($user, $dateFrom,$dateTo, $documentType);
    }
    public function GetDocumentsItem(string $documentNumber, DocumentsType $documentType): DocumentItemsDto
    {
        return $this->erp->GetDocumentsItem($documentNumber,$documentType);
    }
    public function GetCartesset(string $userExId, \DateTimeImmutable $dateFrom, \DateTimeImmutable $dateTo): CartessetDto
    {
        return $this->erp->GetCartesset($userExId,$dateFrom,$dateTo);
    }
    public function PurchaseHistoryByUserAndSku(string $userExtId, string $sku): PurchaseHistory
    {
        return $this->erp->PurchaseHistoryByUserAndSku($userExtId,$sku);
    }

    /** FOR CRON */
    public function GetProducts(?int $pageSize, ?int $skip): ProductsDto
    {
        return $this->erp->GetProducts($pageSize,$skip);
    }

    public function GetSubProducts(): ProductsDto
    {
        return $this->erp->GetSubProducts();
    }

    public function GetUsers(): UsersDto
    {
        return $this->erp->GetUsers();
    }

    public function GetUsersInfo(): UsersDto
    {
        return $this->erp->GetUsersInfo();
    }

    public function GetMigvan():MigvansDto
    {
        return $this->erp->GetMigvan();
    }

    public function GetPrices(): PricesDto
    {
        return $this->erp->GetPrices();
    }

    public function GetMigvansOnline(?array $skus): MigvansDto
    {
        return $this->erp->GetMigvan();
    }

    public function GetStocks(): StocksDto
    {
        return $this->erp->GetStocks();
    }

    public function GetCategories(): CategoriesDto
    {
        return $this->erp->GetCategories();
    }

    public function GetPriceList(): PriceListsDto
    {
        return $this->erp->GetPriceList();
    }

    public function GetPriceListUser(): PriceListsUserDto
    {
        return $this->erp->GetPriceListUser();
    }


    public function GetPriceListDetailed(): PriceListsDetailedDto
    {
        return $this->erp->GetPriceListDetailed();
    }

    public function GetSubUsers(): UsersDto
    {
        return $this->erp->GetSubUsers();
    }

    public function GetPackMain(): PacksMainDto
    {
        return $this->erp->GetPackMain();
    }

    public function GetPackProducts(): PacksProductDto
    {
        return $this->erp->GetPackProducts();
    }

}