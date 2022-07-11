<?php

namespace App\Repository;

use App\Entity\Antiplagiat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Antiplagiat>
 *
 * @method Antiplagiat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Antiplagiat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Antiplagiat[]    findAll()
 * @method Antiplagiat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntiplagiatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Antiplagiat::class);
    }

    public function add(Antiplagiat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Antiplagiat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;

        $order = $this->setSort($sort, 'antiplagiat');

        $qb = $this->createQueryBuilder('antiplagiat')
            ->leftJoin('antiplagiat.discipline', 'discipline')->addSelect('discipline')
            ->leftJoin('antiplagiat.author', 'user')->addSelect('user')
            ->orderBy($order[0], $order[1])
        ;

        // комментарий
        if(!empty($search)) {
            $qb->andWhere("LOWER(antiplagiat.comment) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $result = $qb->getQuery()
            ->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('antiplagiat');

        $qb->select('COUNT(antiplagiat.id)')
            ->leftJoin('antiplagiat.discipline', 'discipline')
            ->leftJoin('antiplagiat.author', 'user')
        ;

        if(!empty($search)) {
            $qb->andWhere("LOWER(antiplagiat.comment) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0 ;
    }

    public function get($id)
    {
        $qb = $this->createQueryBuilder('antiplagiat')
            ->leftJoin('antiplagiat.discipline', 'discipline')
            ->leftJoin('antiplagiat.author', 'user')
            ->where('antiplagiat.id = :id')->setParameter('id', $id)
        ;

        return $qb->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
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
            $order = $prefix . '.id DESC';
        }

        return explode(' ', $order);
    }
}
