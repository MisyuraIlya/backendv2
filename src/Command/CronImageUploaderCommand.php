<?php

namespace App\Command;

use App\Entity\MediaObject;
use App\Entity\ProductImages;
use App\Erp\Core\ErpManager;
use App\Repository\ErrorRepository;
use App\Repository\MediaObjectRepository;
use App\Repository\ProductImagesRepository;
use App\Repository\ProductRepository;
use App\Service\FtpService;
use App\Service\ImageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'CronImageUploader',
    description: 'Add a short description for your command',
)]
class CronImageUploaderCommand extends Command
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductImagesRepository $productImagesRepository,
        private readonly MediaObjectRepository $mediaObjectRepository,
        private readonly HttpClientInterface $httpClient,
    )
    {
        parent::__construct();
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
        $arg1 = $input->getArgument('arg1');
        $products = $this->productRepository->GetAllProducts();
        foreach ($products as $productRec){
                $this->fetchImageFromApi($productRec['sku']);
        }
        $sourceFolder = __DIR__.'/../../public/images';
        $targetFolder = __DIR__.'/../../public/imagesResized';
        $targetSizeBytes = 1024 * 1024; // 1 MB
        (new ImageService())::resizeImagesInFolder($sourceFolder, $targetFolder, $targetSizeBytes);
        $this->SaveImagesInDb();
        (new FtpService('digitrade.com.ua/src/img3/product', 'src/img3/product'))->uploadAllImagesFromResizedFolder();
        $this->DeletAll();
        $io->success('Images Successfuly resied and updated and loaded to ftp');

        return Command::SUCCESS;
    }

    private function SaveImagesInDb()
    {
        $targetFolder = __DIR__.'/../../public/imagesResized';
        if (!is_dir($targetFolder)) {
            throw new \Exception("The 'imagesResized' folder does not exist locally.");
        }

        $localFiles = scandir($targetFolder);
        foreach ($localFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                if (preg_match('/^(.+)\..+?$/', $file, $matches)) {
                    $fileName = $matches[1];
                    $product = $this->productRepository->findOneBySku($fileName);
                    $isExistMediaObject = $this->mediaObjectRepository->findOneByFilePath($file);
                    if($product && !$isExistMediaObject){
                        $newMedia = new MediaObject();
                        $newMedia->setSource('product');
                        $newMedia->setCreatedAt(new \DateTimeImmutable());
                        $newMedia->setFilePath($file);
                        $this->mediaObjectRepository->save($newMedia,true);

                        $newProductImage = new ProductImages();
                        $newProductImage->setProduct($product);
                        $newProductImage->setMediaObject($newMedia);
                        $this->productImagesRepository->save($newProductImage);

                        $product->setUpdatedAt(new \DateTimeImmutable());
                        $product->setDefaultImagePath($file);
                        $this->productRepository->createProduct($product,true);
                    }
                }
            }
        }
    }

    private function DeletAll()
    {
        $targetFolder = __DIR__.'/../../public/images';
        if (!is_dir($targetFolder)) {
            throw new \Exception("The 'images' folder does not exist locally.");
        }

        $files = glob($targetFolder . '/*'); // Get all file names in the folder
        foreach($files as $file) { // Iterate through each file
            if(is_file($file)) {
                unlink($file); // Delete the file
            }
        }

        $targetFolder = __DIR__.'/../../public/imagesResized';
        if (!is_dir($targetFolder)) {
            throw new \Exception("The 'imagesResized' folder does not exist locally.");
        }
        $files = glob($targetFolder . '/*'); // Get all file names in the folder
        foreach($files as $file) { // Iterate through each file
            if(is_file($file)) {
                unlink($file); // Delete the file
            }
        }

    }

    private function fetchImageFromApi(string $sku)
    {
        $res = (new ErpManager($this->httpClient))->GetProductImage($sku);
        try {
            if($res->image){
                list($type, $data) = explode(';', $res->image);
                list(, $data)      = explode(',', $data);
                list(, $suffix)    = explode('/', $type);
                $decodedData = base64_decode($data);
                file_put_contents("public/imagesResized/$sku." . $suffix, $decodedData);
            }
        } catch (\Exception $e) {

        }

    }
}
