<?php

namespace App\Repository;

use App\Entity\SubAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubAttribute>
 *
 * @method SubAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubAttribute[]    findAll()
 * @method SubAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubAttribute::class);
    }

    public function createSubAttribute(SubAttribute $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySkuAndAttributeMain(string $sku, string $attribute): ?SubAttribute
    {
        return $this->createQueryBuilder('a')
            ->where('a.product = :val1')
            ->andWhere('a.attribute = :val2')
            ->setParameter('val1', $sku)
            ->setParameter('val2', $attribute)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByTitle( ?string $title): ?SubAttribute
    {
        return $this->createQueryBuilder('a')
            ->where('a.title = :val1')
            ->setParameter('val1', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return SubAttribute[] Returns an array of SubAttribute objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubAttribute
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
