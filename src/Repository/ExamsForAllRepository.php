<?php

namespace App\Repository;

use App\Entity\ExamsForAll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamsForAll|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamsForAll|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamsForAll[]    findAll()
 * @method ExamsForAll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamsForAllRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamsForAll::class);
    }

//    /**
//     * @return ExamsForAll[] Returns an array of ExamsForAll objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExamsForAll
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
