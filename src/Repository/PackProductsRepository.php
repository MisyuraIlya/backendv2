<?php

namespace App\Repository;

use App\Entity\PackProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackProducts>
 *
 * @method PackProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackProducts[]    findAll()
 * @method PackProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackProducts::class);
    }

    public function findOneByProductIdAndPackId(?string $product, ?string $pack): ?PackProducts
    {
        return $this->createQueryBuilder('p')
            ->where('p.product = :val1')
            ->andWhere('p.pack = :val2')
            ->setParameter('val1', $product)
            ->setParameter('val2', $pack)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(PackProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PackProducts[] Returns an array of PackProducts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PackProducts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
