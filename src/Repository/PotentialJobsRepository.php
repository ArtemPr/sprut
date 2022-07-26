<?php

namespace App\Repository;

use App\Entity\PotentialJobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PotentialJobs>
 *
 * @method PotentialJobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method PotentialJobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method PotentialJobs[]    findAll()
 * @method PotentialJobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PotentialJobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PotentialJobs::class);
    }

    public function add(PotentialJobs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PotentialJobs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();
        $order = $this->setSort($sort, 'pj');
        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;
        $qb = $this->createQueryBuilder('pj')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);
        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );
        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('pj');
        $qb->select('COUNT(pj.id)');
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
            'SELECT pj
                FROM App\Entity\PotentialJobs pj
                WHERE pj.id = :id'
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
            $order = $prefix.'.jobs_name DESC';
        }
        return explode(' ', $order);
    }
}
