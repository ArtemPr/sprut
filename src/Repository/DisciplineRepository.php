<?php

namespace App\Repository;

use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discipline>
 *
 * @method Discipline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discipline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discipline[]    findAll()
 * @method Discipline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisciplineRepository extends ServiceEntityRepository
{
    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;

        parent::__construct($registry, Discipline::class);
    }

    public function add(Discipline $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Discipline $entity, bool $flush = false): void
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
        $search = !empty($search) ? strtolower($search) : null;
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;
        $order = $this->setSort($sort, 'ds');

        $qb = $this->createQueryBuilder('ds')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if (!empty($search)) {
            $qb->where("LOWER(ds.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('ds');

        $qb->select('COUNT(ds.id)');

        if(!empty($search)) {
            $qb->andWhere("LOWER(ds.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0 ;
    }

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
            $order = $prefix . '.name DESC';
        }

        return explode(' ', $order);
    }

    public function get($id)
    {
        $qb = $this->createQueryBuilder('discipline')
        //   ->leftJoin('antiplagiat.discipline', 'discipline')->addSelect('discipline')
            ->where('discipline.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );
        return $result;
    }

    // var 1 working
//    public function getAntiplagiats($id)
//    {
//        $antiplagiatItems = $this->registry
//            ->getRepository(Antiplagiat::class)
//            ->findBy([
//                'discipline' => $id
//            ]);
//
//        dd([
//            '$antiplagiatItems' => $antiplagiatItems ?? '-',
//        ]);
//    }
}
