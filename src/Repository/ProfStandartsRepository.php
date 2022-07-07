<?php

namespace App\Repository;

use App\Entity\ProfStandarts;
use App\Service\QueryHelper;
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
    public const PER_PAGE = 500;

    use QueryHelper;

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
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, ?string $search = null)
    {
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;
        $order = $this->setSort($sort, 'op');

        $qb = $this->createQueryBuilder('op')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if(!empty($search)) {
            $qb->where("LOWER(op.name) LIKE :search ESCAPE '!'")
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
        $qb = $this->createQueryBuilder('op');

        $qb->select('COUNT(op.id)');

        if(!empty($search)) {
            $qb->andWhere("LOWER(op.name) LIKE :search ESCAPE '!'")
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
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT ps
                FROM App\Entity\ProfStandarts ps
                WHERE ps.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
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
