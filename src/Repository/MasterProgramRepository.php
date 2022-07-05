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
            ->setMaxResults($on_page);

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

    /**
     * @param int $id
     * @return array|null
     */
    public function getProgram(int $id): array|null
    {
        return $this->get($id);
    }


    /**
     * @param $sort
     * @param $prefix
     *
     * @return array
     */
    private function setSort(?string $sort, string $prefix): array
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
