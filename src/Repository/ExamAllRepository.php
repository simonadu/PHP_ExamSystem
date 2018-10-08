<?php

namespace App\Repository;

use App\Entity\ExamAll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamAll|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamAll|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamAll[]    findAll()
 * @method ExamAll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamAllRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamAll::class);
    }

//    /**
//     * @return ExamAll[] Returns an array of ExamAll objects
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
    public function findOneBySomeField($value): ?ExamAll
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
