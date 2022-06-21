<?php

namespace App\Repository;

use App\Entity\Kaferda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kaferda>
 *
 * @method Kaferda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kaferda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kaferda[]    findAll()
 * @method Kaferda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KaferdaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kaferda::class);
    }

    public function add(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 5)
    {
        $entityManager = $this->getEntityManager();

        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;

        $result = $entityManager->createQuery(
            'SELECT op, dir, tc
                FROM App\Entity\Kaferda op
                LEFT JOIN op.director dir
                LEFT JOIN op.training_centre tc
                ORDER BY op.id'
        )->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
