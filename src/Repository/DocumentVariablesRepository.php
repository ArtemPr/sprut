<?php

namespace App\Repository;

use App\Entity\DocumentsVariables;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentsVariables>
 *
 * @method DocumentsVariables|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentsVariables|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentsVariables[]    findAll()
 * @method DocumentsVariables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentVariablesRepository extends ServiceEntityRepository
{
    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentsVariables::class);
    }

    public function add(DocumentsVariables $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DocumentsVariables $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $order = $this->setSort($sort, 'dv');

        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;
        $search = !empty($search) ? strtolower($search) : null;

        $qb = $this->createQueryBuilder('dv')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if (!empty($search)) {
            $qb->andWhere("LOWER(dt.template_name) LIKE :search ESCAPE '!'")
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
        $qb = $this->createQueryBuilder('dv');

        $qb->select('COUNT(dv.id)');

        if (!empty($search)) {
            $qb->andWhere("LOWER(dt.template_name) LIKE :search ESCAPE '!'")
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
        $qb = $this->createQueryBuilder('dv');

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

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
            $order = $prefix.'.name DESC';
        }

        return explode(' ', $order);
    }
}
