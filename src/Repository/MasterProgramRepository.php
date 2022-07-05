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
     * Применяется для API запросов
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
     * Применяется для API запросов
     * @param string|null $param
     *
     * @return array
     */
    public function getProgramList(int $page = 0, int|null $max_result = 0, array|null $param = null): array|null
    {
        return $this->getList([
            'page' => $page,
            'on_page' => $max_result,

        ]);
    }

    /**
     * Вывод списка программ
     */
    public function getList(array $param = []): ?array
    {
        $page = !empty($param['page']) ? (int) $param['page'] : 0;
        $on_page = !empty($param['on_page']) ? (int) $param['on_page'] : null;
        $sort = !empty($param['sort']) ? (string) $param['sort'] : null;
        $type = !empty($param['type']) ? (string) $param['type'] : null;

        $page = (empty($page) || 1 === $page || 0 === $page) ? 0 : $page - 1;
        $first_result = (int) $page * (int) $on_page;

        $order = $this->setSort($sort, 'program');

        $qb = $this->createQueryBuilder('program')
            ->leftJoin('program.program_type', 'program_type')->addSelect('program_type')
            ->leftJoin('program.federal_standart', 'federal_standart')->addSelect('federal_standart')
            ->leftJoin('program.federal_standart_competencies', 'federal_standart_competencies')->addSelect(
                'federal_standart_competencies'
            )
            ->leftJoin('program.prof_standarts', 'prof_standarts')->addSelect('prof_standarts')
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);<<<<<<< admhome

        if (!empty($type)) {
            $qb->where('program.program_type = :type')
                ->setParameter(':type', $type);
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result;
    }

    /**
     * @return array|mixed
     */
    public function get(int $id)
    {
        $qb = $this->createQueryBuilder('program')
            ->where('program.id = :id')
            ->leftJoin('program.program_type', 'program_type')->addSelect('program_type')
            ->leftJoin('program.federal_standart', 'federal_standart')->addSelect('federal_standart')
            ->setParameters([
                'id' => $id,
            ]);

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0] ?? [];
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
                ->setParameter('search', $this->makeLikeParam($search));
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
                $order = $prefix.'.'.$sort;
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

        return explode(' ', $order);
    }

}
