<?php

namespace App\Repository;

use App\Entity\ToyRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToyRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToyRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToyRequest[]    findAll()
 * @method ToyRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToyRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToyRequest::class);
    }

    // /**
    //  * @return ToyRequest[] Returns an array of ToyRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ToyRequest
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
