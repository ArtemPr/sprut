<?php

namespace App\Repository;

use App\Entity\MasterProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MasterProgram>
 *
 * @method MasterProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterProgram[]    findAll()
 * @method MasterProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterProgramRepository extends ServiceEntityRepository
{
    public const ON_PAGE = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MasterProgram::class);
    }

    public function add(MasterProgram $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MasterProgram $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array
     */
    public function getApiProgramInfo(): array|null
    {
        $entityManager = $this->getEntityManager();
        $query_item = $entityManager->createQuery(
            'SELECT COUNT(pr) AS count_program
                FROM App\Entity\MasterProgram pr'
        );

        $out = $query_item->getResult(Query::HYDRATE_ARRAY) ?? [];

        return $out[0] ?? [];
    }

    /**
     * @param string|null $param
     *
     * @return array
     */
    public function getProgramList(int $page = 0, int|null $max_result = 0, string|null $param = null): array|null
    {
        $entityManager = $this->getEntityManager();

        $max_result = (!empty($max_result) && $max_result > 1) ? $max_result : self::ON_PAGE;

        $sql = 'SELECT pr, pt
                FROM App\Entity\MasterProgram pr
                INNER JOIN pr.program_type pt';

        if (!empty($param['order'])) {
            $col = array_key_first($param['order']);
            $type_sort = ucfirst($param['order'][$col]);
            $sql .= 'ORDER BY pr.'.$col.' '.$type_sort;
        }

        $query_item = $entityManager->createQuery($sql)
            ->setMaxResults($max_result)
            ->setFirstResult($page * $max_result);

        return $query_item->getResult(Query::HYDRATE_ARRAY) ?? [];
    }

    public function getProgram(int $id): array|null
    {
        $entityManager = $this->getEntityManager();
        $query_item = $entityManager->createQuery(
            'SELECT pr, pt
                FROM App\Entity\MasterProgram pr
                INNER JOIN pr.program_type pt
                WHERE pr.id = :id'
        )->setParameter('id', $id);

        return $query_item->getResult(Query::HYDRATE_ARRAY)[0] ?? null;
    }

    /**
     * @param string|null $param
     * @TODO УДАЛИТЬ после залива не сервер всей локали
     */
    public function getApiList(int $page = 0, int|null $max_result = 0, string|null $param = null): array
    {
        return $this->getProgramList($page, $max_result, $param);
    }
}
