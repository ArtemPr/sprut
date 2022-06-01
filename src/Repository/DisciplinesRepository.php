<?php

namespace App\Repository;

use App\Entity\Disciplines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Disciplines>
 *
 * @method Disciplines|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disciplines|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disciplines[]    findAll()
 * @method Disciplines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisciplinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disciplines::class);
    }

    public function add(Disciplines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Disciplines $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
