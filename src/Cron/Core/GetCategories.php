<?php

namespace App\Cron\Core;

use App\Entity\Category;
use App\Entity\Error;
use App\Enum\CategoryEnum;
use App\Erp\Core\ErpManager;
use App\Repository\CategoryRepository;
use App\Repository\ErrorRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCategories
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private CategoryRepository $categoryRepository,
        private readonly ErrorRepository $errorRepository,
    )
    {
    }

    private function createManualLvl1Categories()
    {
        $categories = ['בתי אבות','מרכזים רפואיים','בתי חולים וקופות חולים ','יבוא תרופות מיוחדות','מרפאות ווטרינריות','בתי מרקחת'];
        foreach ($categories as $key => $categoryTitle){
            $category = $this->categoryRepository->findOneByTitle($categoryTitle);
            if(empty($category)){
                $category = new Category();
                $category->setExtId(000);
                $category->setLvlNumber(1);
                $category->setTitle($categoryTitle);
                $category->setIsPublished(true);
                $category->setIdentify($this->handleIdentify($key + 1));
                $category->setIsBlockedForView(0);
                $this->categoryRepository->createCategory($category,true);
            }
        }
    }

    private function createCategoriesLvl2()
    {
//        $productLvl2Arr = [];
//        $productsWithLvl2 = (new ErpManager($this->httpClient, $this->errorRepository))->GetProductsByIdentify($category->getIdentify());
//        foreach ($productsWithLvl2->products as $ItemRec){
//            $productLvl2Arr[] = $ItemRec->categoryLvl2Id;
//        }
//        $uniqueArray = array_unique($productLvl2Arr);

        $response = (new ErpManager($this->httpClient,$this->errorRepository))->GetCategoriesLvl2();
        foreach ($response->categories as $catRec) {
                $categoryLvl2 = $this->categoryRepository->findOneByExtIdAndLvlNumber($catRec->categoryId, 2);
                if(!$categoryLvl2){
                    $categoryLvl2 = new Category();
                    $categoryLvl2->setExtId($catRec->categoryId);
                }
                $categoryLvl2->setIsPublished(true);
                $categoryLvl2->setLvlNumber(2);
                $categoryLvl2->setTitle($catRec->categoryName);
                $categoryLvl2->setIdentify('humane');
                $categoryLvl2->setIsBlockedForView(0);
                $this->categoryRepository->createCategory($categoryLvl2, true);
        }
    }

    private function createCategoriesLvl3()
    {

        $response = (new ErpManager($this->httpClient,$this->errorRepository))->GetCategoriesLvl3();
        foreach ($response->categories as $catRec) {
            $categoryLvl2 = $this->categoryRepository->findOneByExtId($catRec->parentId);
            if($categoryLvl2){
                $existCategoryLvl3 = $this->categoryRepository->findOneByExtIdAndParentId($catRec->categoryId, $categoryLvl2->getId());
                if(!$existCategoryLvl3){
                    $existCategoryLvl3 = new Category();
                    $existCategoryLvl3->setExtId($catRec->categoryId);
                }
                $existCategoryLvl3->setParent($categoryLvl2);
                $existCategoryLvl3->setIsPublished(true);
                $existCategoryLvl3->setLvlNumber(3);
                $existCategoryLvl3->setTitle($catRec->categoryName);
                $existCategoryLvl3->setIdentify($categoryLvl2->getIdentify());
                $existCategoryLvl3->setIsBlockedForView(0);
                $this->categoryRepository->createCategory($existCategoryLvl3, true);
            }
        }
    }

    private function HandleCategoryLvl1Parent()
    {

    }

    private function handleIdentify(int $id)
    {
        if($id == 1) {
            return CategoryEnum::HUMANE->value;
        } else if ($id == 2){
            return CategoryEnum::MEDICAL_CENTER->value;
        } else if($id == 3) {
            return CategoryEnum::HOSPITAL->value;
        } else if($id == 4) {
            return CategoryEnum::DRUG_NOT_IN_BASKET->value;
        } else if($id == 5) {
            return CategoryEnum::VETERINARY->value;
        } else if($id == 6) {
            return CategoryEnum::PHARMECIES->value;
        } else {
            return '';
        }
    }

    public function sync()
    {
        $this->createManualLvl1Categories();
        $this->createCategoriesLvl2();
        $this->createCategoriesLvl3();
    }
}