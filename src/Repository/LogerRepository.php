<?php

namespace App\Repository;

use App\Entity\Loger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loger>
 *
 * @method Loger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loger[]    findAll()
 * @method Loger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogerRepository extends ServiceEntityRepository
{
    const PER_PAGE = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loger::class);
    }

    public function add(Loger $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Loger $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return float|int|mixed|string
     */
    public function getList(int|null $page = 0, int|null $on_page = 25)
    {
        $entityManager = $this->getEntityManager();
        $first_result = $page === 1 ? 0 : (int)$page * (int)$on_page;

        $result = $entityManager->createQuery(
            'SELECT log, us
                FROM App\Entity\Loger log
                JOIN log.user_loger us
                ORDER BY log.id DESC'
        )->setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function getActionList()
    {
        $entityManager = $this->getEntityManager();

        $result_log = $entityManager->createQuery(
            'SELECT log_action
                FROM App\Entity\LogerAction log_action'
        )->getResult(Query::HYDRATE_ARRAY);

        return $result_log;
    }

}
