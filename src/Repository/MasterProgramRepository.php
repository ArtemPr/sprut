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
    const ON_PAGE = 20;

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

    public function getApiList(int $page = 0, int|null $max_result = 0, string|null $param = null): array
    {
        $entityManager = $this->getEntityManager();

        $max_result = (!empty($max_result) && $max_result > 1)
            ?
            $max_result
            :
            self::ON_PAGE;

        $query_item = $entityManager->createQuery(
            'SELECT pr
                FROM App\Entity\MasterProgram pr'
        )->setMaxResults($max_result)->setFirstResult($page * $max_result);

        return $query_item->getResult(Query::HYDRATE_ARRAY) ?? [];
    }

    public function getApiProgramInfo(): array
    {
        $entityManager = $this->getEntityManager();
        $query_item = $entityManager->createQuery(
            'SELECT COUNT(pr) AS count_program
                FROM App\Entity\MasterProgram pr'
        );

        $out =  $query_item->getResult(Query::HYDRATE_ARRAY) ?? [];

        return $out[0] ?? [];
    }
}
