<?php

namespace App\Command;

use App\Cron\Core\GetCategories;
use App\Cron\Core\GetMainAttributes;
use App\Cron\Core\GetMigvans;
use App\Cron\Core\GetPacks;
use App\Cron\Core\GetPriceList;
use App\Cron\Core\GetPriceListDetailed;
use App\Cron\Core\GetPriceListUser;
use App\Cron\Core\GetProductPacks;
use App\Cron\Core\GetProducts;
use App\Cron\Core\GetStocks;
use App\Cron\Core\GetSubAttributes;
use App\Cron\Core\GetUsers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'CronManager',
    description: 'Add a short description for your command',
)]
class CronManagerCommand extends Command
{
    private bool $isOnlinePrice;
    private bool $isOnlineMigvan;
    private bool $isUsedMigvan;

    public function __construct(
        private readonly GetUsers $users,
        private readonly GetCategories $categories,
        private readonly GetProducts $products,
        private readonly GetPriceList $priceList,
        private readonly GetPriceListDetailed $priceListDetailed,
        private readonly GetPriceListUser $priceListUser,
        private readonly GetPacks $packs,
        private readonly GetProductPacks $productPacks,
        private readonly GetStocks $stocks,
        private readonly GetMainAttributes $mainAttributes,
        private readonly GetSubAttributes $subAttributes,
        private readonly GetMigvans $migvans,
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
        $this->users->sync();
        $this->categories->sync();
        $this->products->sync();
        $this->priceList->sync();
        $this->priceListDetailed->sync();
        $this->priceListUser->sync();
        $this->packs->sync();
        $this->productPacks->sync();
        $this->stocks->sync();
        $this->mainAttributes->sync();
        $this->subAttributes->sync();
        if(!$this->isOnlineMigvan && $this->isUsedMigvan){
            $this->migvans->sync();
        }
        $io->success('All Cron Function Executed');
        return Command::SUCCESS;
    }
}
