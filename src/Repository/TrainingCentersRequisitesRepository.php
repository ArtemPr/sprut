<?php

namespace App\Repository;

use App\Entity\TrainingCentersRequisites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingCentersRequisites>
 *
 * @method TrainingCentersRequisites|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingCentersRequisites|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingCentersRequisites[]    findAll()
 * @method TrainingCentersRequisites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingCentersRequisitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingCentersRequisites::class);
    }

    public function add(TrainingCentersRequisites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TrainingCentersRequisites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TrainingCentersRequisites[] Returns an array of TrainingCentersRequisites objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrainingCentersRequisites
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
