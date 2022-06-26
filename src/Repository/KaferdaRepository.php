<?php

namespace App\Repository;

use App\Entity\Kaferda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kaferda>
 *
 * @method Kaferda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kaferda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kaferda[]    findAll()
 * @method Kaferda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KaferdaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kaferda::class);
    }

    public function add(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Kaferda $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null)
    {
        $entityManager = $this->getEntityManager();

        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;

        if (!is_null($sort)) {
            if (strstr($sort, '__up')) {
                $sort = str_replace('__up', ' DESC', $sort);
            } else {
                $sort .= " ASC";
            }

            if (!strstr($sort, '.')) {
                $order = 'kafedra.' . $sort;
            } else {
                $order = $sort;
            }
        } else {
            $order = 'kafedra.id DESC';
        }
        dump($order);
        $result = $entityManager->createQuery(
            'SELECT kafedra, director, training_centre
                FROM App\Entity\Kaferda kafedra
                LEFT JOIN kafedra.director director
                LEFT JOIN kafedra.training_centre training_centre
                WHERE kafedra.delete = :delete
                ORDER BY ' . $order
        )->
            setParameter('delete', false)->
            setFirstResult($first_result)
            ->setMaxResults($on_page)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function get($id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT kafedra, director, training_centre
                FROM App\Entity\Kaferda kafedra
                LEFT JOIN kafedra.director director
                LEFT JOIN kafedra.training_centre training_centre
                WHERE kafedra.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
