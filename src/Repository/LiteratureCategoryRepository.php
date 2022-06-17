<?php

namespace App\Repository;

use App\Entity\LiteratureCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LiteratureCategory>
 *
 * @method LiteratureCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiteratureCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiteratureCategory[]    findAll()
 * @method LiteratureCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiteratureCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiteratureCategory::class);
    }

    public function add(LiteratureCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LiteratureCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
