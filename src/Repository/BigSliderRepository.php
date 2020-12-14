<?php

namespace App\Repository;

use App\Entity\BigSlider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BigSlider|null find($id, $lockMode = null, $lockVersion = null)
 * @method BigSlider|null findOneBy(array $criteria, array $orderBy = null)
 * @method BigSlider[]    findAll()
 * @method BigSlider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BigSliderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BigSlider::class);
    }

    // /**
    //  * @return BigSlider[] Returns an array of BigSlider objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BigSlider
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
