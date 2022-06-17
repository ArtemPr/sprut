<?php

namespace App\Repository;

use App\Entity\ProfStandarts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfStandarts>
 *
 * @method ProfStandarts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfStandarts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfStandarts[]    findAll()
 * @method ProfStandarts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfStandartsRepository extends ServiceEntityRepository
{
    const PER_PAGE = 500;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfStandarts::class);
    }

    public function add(ProfStandarts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfStandarts $entity, bool $flush = false): void
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
                FROM App\Entity\ProfStandarts op'
        )->setMaxResults(self::PER_PAGE)->getResult(Query::HYDRATE_ARRAY);
        return $result;
    }
}
