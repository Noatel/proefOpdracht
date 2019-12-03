<?php

namespace App\Repository;

use App\Entity\OrderRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrderRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderRule[]    findAll()
 * @method OrderRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderRule::class);
    }

    // /**
    //  * @return OrderRule[] Returns an array of OrderRule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderRule
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
