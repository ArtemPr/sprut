<?php

namespace App\Repository;

use App\Entity\Antiplagiat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Antiplagiat>
 *
 * @method Antiplagiat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Antiplagiat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Antiplagiat[]    findAll()
 * @method Antiplagiat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntiplagiatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Antiplagiat::class);
    }

    public function add(Antiplagiat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Antiplagiat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Antiplagiat[] Returns an array of Antiplagiat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Antiplagiat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
