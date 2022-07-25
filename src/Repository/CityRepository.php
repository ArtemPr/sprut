<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function add(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT city
                FROM App\Entity\City city
                ORDER BY city.name ASC'
        )->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('city');

        $qb->select('COUNT(city.id)');

        if (!empty($search)) {
            $qb->andWhere("LOWER(city.name) LIKE :search ESCAPE '!'")
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
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT city
                FROM App\Entity\City city
                WHERE city.id = :id'
        )->setParameter('id', $id)
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
