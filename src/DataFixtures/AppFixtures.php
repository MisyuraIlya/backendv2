<?php

namespace App\DataFixtures;

use App\Entity\SubProduct;
use App\Factory\SubAttributeFactory;
use App\Factory\SubProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Factory\ProductFactory;
use App\Factory\CategoryFactory;
use App\Factory\PriceListFactory;
use App\Factory\UserFactory;
use App\Factory\ProductImagesFactory;
use App\Factory\PriceListDetailedFactory;
use App\Factory\UserInfoFactory;
use App\Factory\MigvanFactory;
use App\Factory\AttributeMainFactory;
use App\Factory\SubUserFactory;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CategoryFactory::createMany(40);
        ProductFactory::createMany(500, function () {
            return [
                'categoryLvl1' => CategoryFactory::random(),
                'categoryLvl2' => CategoryFactory::random(),
                'categoryLvl3' => CategoryFactory::random(),
            ];
        });
        ProductImagesFactory::createMany(500, function () {
            return [
                'product' => ProductFactory::random(),
            ];
        });
        PriceListFactory::createMany(10);
        PriceListDetailedFactory::createMany(500, function () {
            return [
                'product' => ProductFactory::random(),
                'priceList' => PriceListFactory::random(),
            ];
        });
        UserFactory::createMany(30, function () {
            return [
                'priceList' => PriceListFactory::random(),
            ];
        });
//        UserInfoFactory::create(500, function () {
//            return [
//                'user' => UserFactory::random(),
//            ];
//        });
        MigvanFactory::createMany(500, function () {
            return [
                'user' => UserFactory::random(),
                'sku' => ProductFactory::random(),
            ];
        });

        AttributeMainFactory::createMany(5);
        SubAttributeFactory::createMany(50, function () {
            return [
                'attribute' => AttributeMainFactory::random(),
                'product' => ProductFactory::random(),
            ];
        });

    }
}
