<?php

namespace App\Repository;

use App\Entity\Subdivisions;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subdivisions>
 *
 * @method Subdivisions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subdivisions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subdivisions[]    findAll()
 * @method Subdivisions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubdivisionsRepository extends ServiceEntityRepository
{
    use QueryHelper;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subdivisions::class);
    }

    public function add(Subdivisions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subdivisions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();
        $order = $this->setSort($sort, 'sb');
        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;
        $qb = $this->createQueryBuilder('sb')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->where('sb.delete = :delete')
            ->setParameter('delete', false);
        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );
        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('sb');
        $qb->select('COUNT(sb.id)')
            ->where('sb.delete = :delete')
            ->setParameter('delete', false);
        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );
        return $result[0][1] ?? 0;
    }

    public function get($id)
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery(
            'SELECT sb
                FROM App\Entity\Subdivisions sb
                WHERE sb.id = :id'
        )->setParameter('id', $id)->getResult(Query::HYDRATE_ARRAY);
    }

    private function setSort($sort, $prefix): array
    {
        if (!is_null($sort)) {
            if (strstr($sort, '__up')) {
                $sort = str_replace('__up', ' DESC', $sort);
            } else {
                $sort .= ' ASC';
            }
            if (!strstr($sort, '.')) {
                $order = $prefix.'.'.$sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = $prefix.'.subdivisions_name DESC';
        }
        return explode(' ', $order);
    }
}
