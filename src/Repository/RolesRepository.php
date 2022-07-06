<?php

namespace App\Repository;

use App\Entity\Roles;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Roles>
 *
 * @method Roles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roles[]    findAll()
 * @method Roles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesRepository extends ServiceEntityRepository
{
    public const PER_PAGE = 25;

    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Roles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Roles $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Roles $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     */
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();
        $search = !empty($search) ? strtolower($search) : null;

        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;

        $first_result = (int)$page * (int)$on_page;


        $order = $this->setSort($sort, 'role');

        $qb = $this->createQueryBuilder('role')
            ->orderBy($order[0], $order[1])
            ->where('role.delete = :delete')
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if (!empty($search)) {
            $qb->andWhere("LOWER(role.name) LIKE :search ESCAPE '!'")
                ->setParameters(
                    [
                        'search' => $this->makeLikeParam($search),
                        'delete' => false
                    ]
                );
        } else {
            $qb->setParameter('delete', false);
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('role');

        if(!empty($search)) {
            $qb->select('COUNT(role.id)')
                ->where('role.delete = :delete')
                ->setParameter('delete', false)
                ->andWhere("LOWER(role.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        } else {
            $qb->select('COUNT(role.id)');
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0 ;
    }

    public function get($id)
    {
        $result = $this->createQueryBuilder('role')
            ->where('role.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute(
                hydrationMode: Query::HYDRATE_ARRAY
            );

        return $result[0] ?? [];
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
}
