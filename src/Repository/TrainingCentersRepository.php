<?php

namespace App\Repository;

use App\Entity\TrainingCenters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingCenters>
 *
 * @method TrainingCenters|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingCenters|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingCenters[]    findAll()
 * @method TrainingCenters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingCentersRepository extends ServiceEntityRepository
{
    const PER_PAGE = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingCenters::class);
    }

    public function add(TrainingCenters $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TrainingCenters $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     */
    public function getList()
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT tc
                FROM App\Entity\TrainingCenters tc'
        )->setMaxResults(self::PER_PAGE)->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
