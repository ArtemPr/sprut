<?php

namespace App\Repository;

use App\Entity\Kaferda;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kaferda>
 *
 * @method Kaferda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kaferda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kaferda[]    findAll()
 * @method Kaferda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KaferdaRepository extends ServiceEntityRepository
{
    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kaferda::class);
    }

    public function add(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;

        $order = $this->setSort($sort, 'kf');

        $qb = $this->createQueryBuilder('kf')
            ->leftJoin('kf.director', 'director')->addSelect('director')
            ->leftJoin('kf.training_centre', 'training_centre')->addSelect('training_centre')
            ->leftJoin('kf.product_line', 'product_line')->addSelect('product_line')
            ->leftJoin('kf.parent', 'parent')->addSelect('parent')
            ->where('kf.delete = :delete')
            ->setParameter('delete', false)
            ->orderBy($order[0], $order[1])
        ;

        if (!empty($search)) {
            $qb->andWhere("LOWER(kf.name) LIKE :search ESCAPE '!'")
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
        $qb = $this->createQueryBuilder('kafedra')
            ->select('COUNT(kafedra.id)')
            ->leftJoin('kafedra.director', 'director')
            ->leftJoin('kafedra.training_centre', 'training_centre')
            ->where('kafedra.delete = :delete')
            ->setParameter('delete', false);

        if (!empty($search)) {
            $qb->andWhere("LOWER(kafedra.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0;
    }

    public function get($id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT kafedra, director, training_centre
                FROM App\Entity\Kaferda kafedra
                LEFT JOIN kafedra.director director
                LEFT JOIN kafedra.training_centre training_centre
                WHERE kafedra.id = :id'
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
                $sort .= ' ASC';
            }

            if (!strstr($sort, '.')) {
                $order = $prefix.'.'.$sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = $prefix.'.id DESC';
        }

        return explode(' ', $order);
    }
}
