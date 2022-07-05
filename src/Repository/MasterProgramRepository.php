<?php

namespace App\Repository;

use App\Entity\MasterProgram;
use App\Entity\ProgramType;
use App\Service\QueryHelper;
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
    use QueryHelper;

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
    public function getProgramList(int $page = 0, int|null $max_result = 0, array|null $param = null): array|null
    {
        $entityManager = $this->getEntityManager();

        $max_result = (!empty($max_result) && $max_result > 1) ? $max_result : self::ON_PAGE;


        $sql = 'SELECT pr, pt
                FROM App\Entity\MasterProgram pr
                LEFT JOIN pr.program_type pt
                ';

        if (!empty($param['order'])) {
            $col = array_key_first($param['order']);
            $type_sort = ucfirst($param['order'][$col]);
            $sql .= 'ORDER BY pr.'.$col.' '.$type_sort;
        } else {
            $sql .= 'ORDER BY pr.id DESC';
        }

        $query_item = $entityManager->createQuery($sql)
            ->setMaxResults($max_result)
            ->setFirstResult($page * $max_result);

        return $query_item->getResult(Query::HYDRATE_ARRAY) ?? [];
    }


    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $type=null, string|null $search = null): array|null
    {
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;

        $qb = $this->createQueryBuilder('pr')
            ->addSelect(['pt', 'fs', 'fsc', 'ps'])
            ->leftJoin('pr.program_type', 'pt')
            ->leftJoin('pr.federal_standart', 'fs')
            ->leftJoin('pr.federal_standart_competencies', 'fsc')
            ->leftJoin('pr.prof_standarts', 'ps')
        ;

        if (!empty($type)) {
            $qb->andWhere('pt.id = :type')
                ->setParameter('type', $type);
        }

        if(!empty($search)) {
            $qb->where("LOWER(pr.name) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $sortDir = 'DESC';

        if (!is_null($sort)) {
            if (strstr($sort, '__up')) {
                $sort = str_replace('__up', '', $sort);
            } else {
                $sort = str_replace('__down', '', $sort);
                $sortDir = 'ASC';
            }

            if (!strstr($sort, '.')) {
                $order = 'pr.' . $sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = 'pr.id';
        }

        $qb->orderBy($order, $sortDir);

        $result = $qb->getQuery()
            ->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
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

    public function get($id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT program, type, fgos
                FROM App\Entity\MasterProgram program
                LEFT JOIN program.program_type type
                LEFT JOIN program.federal_standart fgos
                WHERE program.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result[0] ?? [];
    }
}
