<?php

namespace App\Repository;

use App\Entity\Operations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operations>
 *
 * @method Operations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operations[]    findAll()
 * @method Operations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationsRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operations::class);
    }

    public function add(Operations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Operations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     */
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null)
    {
        $entityManager = $this->getEntityManager();

        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;

        if (!is_null($sort)) {
            if (strstr($sort, '__up')) {
                $sort = str_replace('__up', ' DESC', $sort);
            } else {
                $sort .= " ASC";
            }

            if (!strstr($sort, '.')) {
                $order = 'op.' . $sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = 'op.id DESC';
        }

        $result = $entityManager->createQuery(
            'SELECT op
                FROM App\Entity\Operations op
                ORDER BY ' . $order
        )
            ->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function get($id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT op
                FROM App\Entity\Operations op
                WHERE op.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
