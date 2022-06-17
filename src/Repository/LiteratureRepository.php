<?php

namespace App\Repository;

use App\Entity\Literature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Literature>
 *
 * @method Literature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Literature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Literature[]    findAll()
 * @method Literature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiteratureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Literature::class);
    }

    public function add(Literature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Literature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
