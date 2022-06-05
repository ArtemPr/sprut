<?php

namespace App\Repository;

use App\Entity\FederalStandartCompetencies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FederalStandartCompetencies>
 *
 * @method FederalStandartCompetencies|null find($id, $lockMode = null, $lockVersion = null)
 * @method FederalStandartCompetencies|null findOneBy(array $criteria, array $orderBy = null)
 * @method FederalStandartCompetencies[]    findAll()
 * @method FederalStandartCompetencies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FederalStandartCompetenciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FederalStandartCompetencies::class);
    }

    public function add(FederalStandartCompetencies $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FederalStandartCompetencies $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FederalStandartCompetencies[] Returns an array of FederalStandartCompetencies objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FederalStandartCompetencies
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
