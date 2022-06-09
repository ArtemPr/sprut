<?php

namespace App\Repository;

use App\Entity\ProfStandartsActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfStandartsActivities>
 *
 * @method ProfStandartsActivities|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfStandartsActivities|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfStandartsActivities[]    findAll()
 * @method ProfStandartsActivities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfStandartsActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfStandartsActivities::class);
    }

    public function add(ProfStandartsActivities $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfStandartsActivities $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProfStandartsActivities[] Returns an array of ProfStandartsActivities objects
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

//    public function findOneBySomeField($value): ?ProfStandartsActivities
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
