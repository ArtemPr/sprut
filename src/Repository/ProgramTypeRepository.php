<?php

namespace App\Repository;

use App\Entity\ProgramType;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgramType>
 *
 * @method ProgramType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgramType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgramType[]    findAll()
 * @method ProgramType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramTypeRepository extends ServiceEntityRepository
{
    use QueryHelper;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgramType::class);
    }

    public function add(ProgramType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProgramType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();
        $order = $this->setSort($sort, 'pt');
        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;
        $qb = $this->createQueryBuilder('pt')
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
        $qb = $this->createQueryBuilder('pt');
        $qb->select('COUNT(pt.id)');
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
            'SELECT pt
                FROM App\Entity\ProgramType pt
                WHERE pt.id = :id'
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
            $order = $prefix.'.name_type DESC';
        }
        return explode(' ', $order);
    }
}
