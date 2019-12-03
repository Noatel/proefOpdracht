<?php

namespace App\Repository;

use App\Entity\Productg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Productg|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productg|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productg[]    findAll()
 * @method Productg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Productg::class);
    }

    // /**
    //  * @return Productg[] Returns an array of Productg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Productg
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
