<?php

namespace App\Repository;

use App\Entity\ProfStandartsCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfStandartsCompetences>
 *
 * @method ProfStandartsCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfStandartsCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfStandartsCompetences[]    findAll()
 * @method ProfStandartsCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfStandartsCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfStandartsCompetences::class);
    }

    public function add(ProfStandartsCompetences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfStandartsCompetences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProfStandartsCompetences[] Returns an array of ProfStandartsCompetences objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProfStandartsCompetences
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
