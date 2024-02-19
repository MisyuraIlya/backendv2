<?php

namespace App\Command;

use App\Cron\GetAtarim;
use App\Cron\GetSubProducts;
use App\Cron\GetSubUsers;
use App\Cron\GetUsersInfo;
use App\Erp\Core\ErpManager;
use App\Erp\Custom\CustomMethods;
use App\Repository\AtarimRepository;
use App\Repository\AttributeMainRepository;
use App\Repository\CategoryRepository;
use App\Repository\ErrorRepository;
use App\Repository\MigvanRepository;
use App\Repository\PackMainRepository;
use App\Repository\PackProductsRepository;
use App\Repository\PriceListDetailedRepository;
use App\Repository\PriceListRepository;
use App\Repository\PriceListUserRepository;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductRepository;
use App\Repository\SubAttributeRepository;
use App\Repository\SubProductRepository;
use App\Repository\SubUserRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[AsCommand(
    name: 'CronManager',
    description: 'Add a short description for your command',
)]
class CronManagerCommand extends Command
{
    private $entityManager;
    private bool $isOnlinePrice;
    private bool $isOnlineMigvan;
    private bool $isUsedMigvan;

    public function __construct(
        private HttpClientInterface                  $httpClient,
        private readonly UserRepository              $userRepository,
        private readonly CategoryRepository          $categoryRepository,
        private readonly ProductRepository           $productRepository,
        private readonly PriceListRepository         $priceListRepository,
        private readonly PriceListDetailedRepository $priceListDetailedRepository,
        private readonly MigvanRepository            $migvanRepository,
        private readonly AttributeMainRepository     $attributeMainRepository,
        private readonly SubAttributeRepository      $SubAttributeRepository,
        private readonly ProductAttributeRepository $productAttributeRepository,
        private readonly PriceListUserRepository $priceListUserRepository,
        private readonly PackMainRepository $packMainRepository,
        private readonly PackProductsRepository $packProductsRepository,
        private readonly ErpManager $erpManager,
        private readonly CustomMethods $customMethods,
    )
    {
        parent::__construct();
        $this->isOnlinePrice = $_ENV['IS_ONLINE_PRICE'] === "true";
        $this->isOnlineMigvan = $_ENV['IS_ONLINE_MIGVAN'] === "true";
        $this->isUsedMigvan = $_ENV['IS_USED_MIGVAN'] === "true";
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        dd($this->customMethods->GetOnlineProdImages());
//        (new GetPriceList(
//            $this->httpClient,
//            $this->priceListRepository,
//        ))->sync();

//        (new GetUsers(
//            $this->httpClient,
//            $this->userRepository,
//        ))->sync();

//        (new GetUsersInfo(
//            $this->httpClient,
//            $this->userRepository,
//        ))->sync();

//        (new GetPriceListUser(
//            $this->httpClient,
//            $this->userRepository,
//            $this->priceListRepository,
//            $this->priceListUserRepository,
//        ))->sync();
//
//        (new GetCategories(
//            $this->httpClient,
//            $this->categoryRepository,
//        ))->sync();

//
//        (new GetProducts(
//            $this->httpClient,
//            $this->categoryRepository,
//            $this->productRepository,
//        ))->sync();


//        (new GetPacks(
//            $this->httpClient,
//            $this->packMainRepository,
//        ))->sync();
//
//        (new GetProductPacks(
//            $this->httpClient,
//            $this->packMainRepository,
//            $this->packProductsRepository,
//            $this->productRepository
//        ))->sync();










//        (new GetMainAttributes(
//            $this->httpClient,
//            $this->attributeMainRepository,
//        ))->sync();
//        (new GetSubAttributes(
//            $this->httpClient,
//            $this->SubAttributeRepository,
//            $this->productRepository,
//            $this->attributeMainRepository,
//            $this->productAttributeRepository,
//        ))->sync();


//        if(!$this->isOnlinePrice && !$this->isOnlineMigvan) {
//            (new GetPriceListDetailed(
//                $this->httpClient,
//                $this->productRepository,
//                $this->priceListRepository,
//                $this->priceListDetailedRepository,
//            ))->sync();
//        }
//
//        if(!$this->isUsedMigvan) {
//            (new GetMigvans(
//                $this->httpClient,
//                $this->migvanRepository,
//                $this->userRepository,
//                $this->productRepository,
//            ))->sync();
//        }
//
//        (new GetStocks(
//            $this->httpClient,
//            $this->productRepository,
//        ))->sync();
//
//        (new GetBasePrice(
//            $this->httpClient,
//            $this->productRepository,
//        ))->sync();


        $io->success('All Cron Function Executed');
        return Command::SUCCESS;
    }
}
