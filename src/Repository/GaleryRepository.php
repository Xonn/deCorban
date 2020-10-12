<?php

namespace App\Repository;

use App\Entity\Galery;
use App\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;

/**
 * @method Galery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Galery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Galery[]    findAll()
 * @method Galery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GaleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galery::class);
    }

    /**
     * @return Galery[]
     */
    public function findLatest(): array
    {
        $galery = $this->createQueryBuilder('g')
            ->where('g.isPublished = 1')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        return $galery;
    }

    /**
     * @return Galery[]
     */
    public function findPopular(): array
    {
        $galery = $this->createQueryBuilder('g')
            ->innerJoin('g.userLikes', 'likes')
            ->orderBy('COUNT(likes)')
            ->groupBy('g')
            ->where('g.isPublished = 1')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        return $galery;
    }

    public function findByCategory(Collection $categories): array
    {
        $result = $this->createQueryBuilder('g')
        ->where('g.isPublished = 1')
        ->andWhere(':categories MEMBER OF g.categories')
        ->andWhere(':galery != g.id')
        ->setParameters(['categories' => $categories, 'galery' => $categories->getOwner()->getId()])
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

        return $result;
    }
    // /**
    //  * @return Galery[] Returns an array of Galery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Galery
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
