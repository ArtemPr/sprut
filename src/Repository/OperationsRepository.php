<?php

namespace App\Repository;

use App\Entity\Operations;
use App\Service\QueryHelper;
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

    use QueryHelper;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operations::class);
    }

    /**
     * @param Operations $entity
     * @param bool $flush
     * @return void
     */
    public function add(Operations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Operations $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Operations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int|null $page
     * @param int|null $on_page
     * @param string|null $sort
     * @param string|null $search
     * @return float|int|mixed|string
     */
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;
        $search = !empty($search) ? strtolower($search) : null;

        $order = $this->setSort($sort, 'op');

        $qb = $this->createQueryBuilder('op')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if (!empty($search)) {
            $qb->andWhere("LOWER(op.name) LIKE :search ESCAPE '!'")
                ->setParameters(
                    [
                        'search' => $this->makeLikeParam(mb_strtolower($search))
                    ]
                );
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );
        return $result;
    }

    public function getAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('op');

        $qb->select('COUNT(op.id)');

        if (!empty($search)) {
            $qb->where("LOWER(op.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0 ;
    }

    /**
     * @param $id
     * @return float|int|mixed|string
     */
    public function get($id)
    {
        $qb = $this->createQueryBuilder('op')
            ->where('op.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result;
    }

    /**
     * @param $sort
     * @param $prefix
     * @return string[]
     */
    private function setSort($sort, $prefix)
    {
        if (!is_null($sort)) {
            if (strstr($sort, '__up')) {
                $sort = str_replace('__up', ' DESC', $sort);
            } else {
                $sort .= " ASC";
            }

            if (!strstr($sort, '.')) {
                $order = $prefix . '.' . $sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = $prefix . '.group DESC';
        }

        return explode(' ', $order);
    }
}
