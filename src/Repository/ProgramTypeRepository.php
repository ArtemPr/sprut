<?php

namespace App\Repository;

use App\Entity\ProgramType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgramType>
 *
 * @method ProgramType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramType[]    findAll()
 * @method ProgramType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramType::class);
    }

    public function add(ProgramType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProgramType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
