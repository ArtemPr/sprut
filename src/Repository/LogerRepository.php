<?php

namespace App\Repository;

use App\Entity\Loger;
use App\Service\QueryHelper;
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

    use QueryHelper;

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
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;
        $search = !empty($search) ? strtolower($search) : null;

        $order = $this->setSort($sort, 'log');

        $qb = $this->createQueryBuilder('log')
            ->leftJoin("log.user_loger", "user_loger")->addSelect("user_loger")
            ->orderBy($order[0], $order[1])
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if (!empty($search)) {
            $qb->andWhere("LOWER(log.comment) LIKE :search ESCAPE '!'")
                ->setParameters(
                    [
                        'search' => $this->makeLikeParam($search)
                    ]
                );
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

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
            $order = $prefix . '.time DESC';
        }

        return explode(' ', $order);
    }
}
