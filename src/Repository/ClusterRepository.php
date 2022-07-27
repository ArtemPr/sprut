<?php

namespace App\Repository;

use App\Entity\Cluster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\QueryHelper;

/**
 * @extends ServiceEntityRepository<Cluster>
 *
 * @method Cluster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cluster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cluster[]    findAll()
 * @method Cluster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClusterRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 400;

    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cluster::class);
    }

    public function add(Cluster $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cluster $entity, bool $flush = false): void
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
//        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
//        $first_result = (int) $page * (int) $on_page;
//
//        $order = $this->setSort($sort, 'fs');
//
//        $qb = $this->createQueryBuilder('fs')
//            ->orderBy($order[0], $order[1])
//            ->setFirstResult($first_result)
//            ->setMaxResults($on_page);
//
//        if (!empty($search)) {
//            $qb->where("LOWER(fs.name) LIKE :search ESCAPE '!'")
//                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
//        }
//
//        $query = $qb->getQuery();
//        $result = $query->execute(
//            hydrationMode: Query::HYDRATE_ARRAY
//        );
//
        return [];
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
//        $qb = $this->createQueryBuilder('fs');
//
//        if(!empty($search)) {
//            $qb->select('COUNT(fs.id)')->where("LOWER(fs.name) LIKE :search ESCAPE '!'")
//                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
//        } else {
//            $qb->select('COUNT(fs.id)');
//        }
//
//        $query = $qb->getQuery();
//        $result = $query->execute(
//            hydrationMode: Query::HYDRATE_ARRAY
//        );

//        return $result[0][1] ?? 0 ;
        return 0;
    }

    public function get(int $id)
    {
//        $qb = $this->createQueryBuilder('fs');
//        $qb->where('fs.id = :id')
//            ->setParameters(
//                [
//                    'id' => $id
//                ]
//            );
//
//        $query = $qb->getQuery();
//        $result = $query->execute(
//            hydrationMode: Query::HYDRATE_ARRAY
//        );
//
//        return $result[0] ?? [];
    }

    private function setSort($sort, $prefix)
    {
//        if (!is_null($sort)) {
//            if (strstr($sort, '__up')) {
//                $sort = str_replace('__up', ' DESC', $sort);
//            } else {
//                $sort .= " ASC";
//            }
//
//            if (!strstr($sort, '.')) {
//                $order = $prefix . '.' . $sort;
//            } else {
//                $order = $sort;
//            }
//        } else {
//            $order = $prefix . '.id DESC';
//        }
//
//        return explode(' ', $order);
    }
}
