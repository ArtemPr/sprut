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
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
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

        if (!empty($search)) {
            $sql = "SELECT op
                FROM App\Entity\FederalStandart op
                WHERE op.name LIKE :search
                ORDER BY " . $order;

            $result = $entityManager->createQuery(
                $sql
            )
                ->setParameter('search', '%' . $search . '%')
                ->setFirstResult($first_result)
                ->setMaxResults($on_page)
                ->getResult(Query::HYDRATE_ARRAY);
        } else {
            $sql = 'SELECT op
                FROM App\Entity\FederalStandart op
                ORDER BY ' . $order;

            $result = $entityManager->createQuery(
                $sql
            )
                ->setFirstResult($first_result)
                ->setMaxResults($on_page)
                ->getResult(Query::HYDRATE_ARRAY);
        }


        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();

        if (!empty($search)) {
            $sql = "SELECT op
                FROM App\Entity\FederalStandart op
                WHERE op.name LIKE :search";

            $result = $entityManager->createQuery(
                $sql
            )->setParameter('search', '%' . $search . '%')
                ->getResult(Query::HYDRATE_ARRAY);
        } else {
            $sql = 'SELECT op
                FROM App\Entity\FederalStandart op';

            $result = $entityManager->createQuery(
                $sql
            )->getResult(Query::HYDRATE_ARRAY);
        }


        return $result;
    }
}
