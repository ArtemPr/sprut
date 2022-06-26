<?php

namespace App\Repository;

use App\Entity\TrainingCenters;
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
                $order = 'tc.' . $sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = 'tc.id DESC';
        }

        $result = $entityManager->createQuery(
            'SELECT tc
                FROM App\Entity\TrainingCenters tc
                WHERE tc.delete = :delete
                ORDER BY ' . $order
        )
            ->setParameter('delete', false)
            ->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }


    public function get($id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT tc
                FROM App\Entity\TrainingCenters tc
                WHERE tc.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
