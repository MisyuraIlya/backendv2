<?php

namespace App\Repository;

use App\Entity\History;
use App\Enum\DocumentsType;
use App\Enum\DocumentTypeHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 *
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function save(History $entity, bool $flush = false): ?History
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function findOneById(int $id): ?History
    {
        return $this->createQueryBuilder('h')
            ->where('h.id = :val1')
            ->setParameter('val1', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function historyHandler(\DateTimeImmutable $dateFrom, \DateTimeImmutable $dateTo, string $userId, int $page, ?DocumentsType $documentType = null)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('h')
            ->from(History::class, 'h')
            ->where('h.createdAt >= :dateFrom')
            ->andWhere('h.createdAt <= :dateTo')
            ->andWhere('h.user = :userId')
            ->setMaxResults(10)
            ->setFirstResult(($page - 1) * 10);

        $parameters = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'userId' => $userId,
        ];

        if ($documentType !== null) {
            $queryBuilder->andWhere('h.documentType = :documentType');
            $parameters['documentType'] = $documentType;
        }

        $queryBuilder->setParameters($parameters);

        $histories = $queryBuilder->getQuery()->getResult();
        return $histories;
    }

//    /**
//     * @return History[] Returns an array of History objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?History
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
