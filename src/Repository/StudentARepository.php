<?php

namespace App\Repository;

use App\Entity\StudentA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StudentA|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentA|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentA[]    findAll()
 * @method StudentA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentARepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentA::class);
    }

//    /**
//     * @return StudentA[] Returns an array of StudentA objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentA
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
