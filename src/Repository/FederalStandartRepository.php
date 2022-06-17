<?php

namespace App\Repository;

use App\Entity\FederalStandart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FederalStandart>
 *
 * @method FederalStandart|null find($id, $lockMode = null, $lockVersion = null)
 * @method FederalStandart|null findOneBy(array $criteria, array $orderBy = null)
 * @method FederalStandart[]    findAll()
 * @method FederalStandart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FederalStandartRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 400;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FederalStandart::class);
    }

    public function add(FederalStandart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FederalStandart $entity, bool $flush = false): void
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
            'SELECT op
                FROM App\Entity\FederalStandart op'
        )->setMaxResults(self::PER_PAGE)->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
