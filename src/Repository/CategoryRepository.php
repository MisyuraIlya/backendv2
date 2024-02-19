<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        UserRepository $userRepository,
    )
    {
        $this->userRepository = $userRepository;
        parent::__construct($registry, Category::class);
    }

    public function createCategory(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByExtId(?string $extId): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.extId = :val1')
            ->setParameter('val1', $extId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByExtIdAndIdentify(?string $extId, ?string $identify): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.extId = :val1')
            ->andWhere('c.identify = :val2')
            ->setParameter('val1', $extId)
            ->setParameter('val2', $identify)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllCategoryLvl1(): Array
    {
        return $this->createQueryBuilder('c')
            ->where('c.lvlNumber = 1')
            ->getQuery()
            ->getResult();
    }

    public function findOneByExtIdAndParentId(?string $extId, $parentId): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.extId = :val1')
            ->andWhere('c.parent = :val2')
            ->setParameter('val1', $extId)
            ->setParameter('val2', $parentId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByExtIdAndLvlNumber(?string $extId, int $lvl): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.extId = :val1')
            ->andWhere('c.lvlNumber = :val2')
            ->setParameter('val1', $extId)
            ->setParameter('val2', $lvl)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByTitle(?string $title): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.title = :val1')
            ->setParameter('val1', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCategoriesByMigvanAndSearch(?string $searchValue)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Product::class, 'p')
            ->andWhere('p.isPublished = true');


        if ($searchValue) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('p.title', ':searchValue'));
            $queryBuilder->setParameter('searchValue', '%' . $searchValue . '%');
        }

        $products = $queryBuilder->getQuery()->getResult();
        $prods = [];
        $categoriesLvl2 = [];
        $categoriesLvl3 = [];
        foreach ($products as $product) {
            assert($product instanceof Product);
            if($product->getCategoryLvl2()){
                $categoriesLvl2[] = $product->getCategoryLvl2()->getId();
            }
            if($product->getCategoryLvl3()){
                $categoriesLvl3[] = $product->getCategoryLvl3()->getId();
            }
            $prods[] = $product->getId();
        }
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.productsLvl1', 'p')
            ->where($qb->expr()->in('p.id', ':productIds'))
            ->setParameter('productIds', $prods);

        $result =  $qb->getQuery()->getResult();
        foreach ($result as $itemRec){
            assert($itemRec instanceof Category);
            $newCat2 = [];
            foreach ($itemRec->getCategories()->toArray() as $subCat) {
                assert($subCat instanceof Category);
                $newCat3 = [];
                if(in_array($subCat->getId(), $categoriesLvl2)){
                    $newCat2[] = $subCat;
                    foreach ($subCat->getCategories() as $subCat3) {
                        assert($subCat3 instanceof Category);
                        if(in_array($subCat3->getId(), $categoriesLvl3)){
                            $newCat3[] = $subCat3;
                            $subCat->removeCategory($subCat3);
                        }
                    }
                }
                $itemRec->removeCategory($subCat);
                $subCat->setCategories($newCat3);
            }
            $itemRec->setCategories($newCat2);
        }


        return $result;
    }

    //CUSTOM
    public function MediMarketCategories()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Category::class, 'p')
            ->andWhere('p.lvlNumber = 1')
            ->orderBy('p.orden', 'ASC');

        $result =  $queryBuilder->getQuery()->getResult();
        foreach ($result as $CategoryRec) {
            assert($CategoryRec instanceof  Category);
            $categoryLvl2ExtIds = $this->getLvl2CategoryByIdentify($CategoryRec->getIdentify());
            $categoriesLvl2 = $this->findCategotiesLvl2($categoryLvl2ExtIds);
            $CategoryRec->setCategories($categoriesLvl2);

        }

        foreach ($result as $lvl1){
            assert($lvl1 instanceof  Category);
            foreach ($lvl1->getCategories() as $lvl2) {
                assert($lvl2 instanceof  Category);
                $newArr = [] ;
                foreach ($lvl2->getCategories() as $lvl3) {
                    assert($lvl3 instanceof  Category);
//                    $checkIsActual = $this->checkIsActualCategory($lvl1->getIdentify(),$lvl2->getExtId(), $lvl3->getExtId());
                    $checkIsActual = true;
                    if($checkIsActual){
                        $newArr[] = $lvl3;
                    }
                }

                $lvl2->setCategories($newArr);
            }
        }
        return $result;
    }

    public function DynamicCategories(string $lvl1, string $lvl2, string $lvl3)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c')
            ->from(Category::class, 'c');
        if ($lvl1 != '0' && $lvl2 === '0' && $lvl3 === '0') {
            $queryBuilder->andWhere('c.identify = :identify')
                ->setParameter('identify', $lvl1)
                ->andWhere('c.lvlNumber = 1');

            $result =  $queryBuilder->getQuery()->getResult();
            foreach ($result as $CategoryRec) {
                assert($CategoryRec instanceof  Category);
                $categoryLvl2ExtIds = $this->getLvl2CategoryByIdentify($CategoryRec->getIdentify());
                $categoriesLvl2 = $this->findCategotiesLvl2($categoryLvl2ExtIds);
                $CategoryRec->setCategories($categoriesLvl2);

            }

            return $result;

        } else if ($lvl1 !== '0' && $lvl2 !== '0' && $lvl3 === '0') {
            $queryBuilder->andWhere('c.identify = :identify')
                ->andWhere('c.extId = :extId')
                ->setParameters(['identify' => $lvl1, 'extId' => $lvl2]);
        } else if ($lvl1 !== '0' && $lvl2 !== '0' && $lvl3 !== '0') {
            $queryBuilder->andWhere('c.identify = :identify')
                ->andWhere('c.extId = :extId')
                ->andWhere('c.extId = :extIdLvl3')
                ->setParameters(['identify' => $lvl1, 'extId' => $lvl2, 'extIdLvl3' => $lvl3]);
        } else {
            $queryBuilder->where('c.lvlNumber = 1');
        }
        $result = $queryBuilder->getQuery()->getResult();
        return $result;
    }

    private function getLvl2CategoryByIdentify(string $identify)
    {
        $customIdentify = $this->KeyQueryEnum($identify);
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('p.extLvl2')
            ->from(Product::class, 'p')
            ->andWhere('p.isPublished = true')
            ->andWhere("p.$customIdentify = 1")
            ->groupBy('p.extLvl2');
        return $queryBuilder->getQuery()->getResult();
    }

    private function findCategotiesLvl2(array $extIds)
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from(Category::class, 'c')
            ->andWhere('c.lvlNumber = 2')
            ->andWhere($queryBuilder->expr()->in('c.extId', ':extIds'))
            ->setParameter('extIds', $extIds);

        $result =  $queryBuilder->getQuery()->getResult();

        return $result;
    }

    private function KeyQueryEnum($input)
    {
        if($input === 'humane'){
            return 'isHumane';
        } else if($input === 'veterinary'){
            return 'isVeterinary';
        } else if($input === 'pharmecies') {
            return 'isPharmecies';
        } else if($input === 'medical_center') {
            return 'isMedicalCenter';
        } else if($input === 'hospital') {
            return 'isHospital';
        } else if($input === 'drug_not_in_basket') {
            return 'isDrugNotInBasket';
        }
    }

    private function checkIsActualCategory($identify, $extId2, $extId3): bool
    {
        $customIdentify = $this->KeyQueryEnum($identify);
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('p.extLvl2')
            ->from(Product::class, 'p')
            ->andWhere('p.isPublished = true')
            ->andWhere("p.$customIdentify = 1")
            ->andWhere("p.extLvl2 = :extId2")
            ->andWhere("p.extLvl3 = :extId3")
            ->setParameter('extId2', $extId2)
            ->setParameter('extId3', $extId3);
        $result =  $queryBuilder->getQuery()->getResult();
        if(!empty($result)){
            return true;
        } else {
            return false;
        }
    }


}
