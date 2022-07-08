<?php

namespace App\Repository;

use App\Entity\TrainingCenters;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<TrainingCenters>
 *
 * @method TrainingCenters|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingCenters|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingCenters[]    findAll()
 * @method TrainingCenters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingCentersRepository extends ServiceEntityRepository
{
    const PER_PAGE = 25;

    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingCenters::class);
    }

    public function add(TrainingCenters $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TrainingCenters $entity, bool $flush = false): void
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

        $order = $this->setSort($sort, 'tc');

        $qb = $this->createQueryBuilder('tc')
            ->where('tc.delete = :delete')
            ->setParameter('delete', false)
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if(!empty($search)) {
            $qb->andWhere("LOWER(tc.name) LIKE :search ESCAPE '!'")
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
        $qb = $this->createQueryBuilder('tc');

        $qb->select('COUNT(tc.id)')
            ->where('tc.delete = :delete')
            ->setParameter('delete', false);

        if(!empty($search)) {
            $qb->andWhere("LOWER(tc.name) LIKE :search ESCAPE '!'")
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

        $qb = $this->createQueryBuilder('training_centers')
            ->where('training_centers.id = :id')
            ->leftJoin('training_centers.trainingCentersRequisites', 'training_centers_requisites')->addSelect('training_centers_requisites')
            ->setParameter('id', $id);

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        if (!empty($result['trainingCentersRequisites'])) {

        }

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
