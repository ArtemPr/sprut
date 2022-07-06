<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\QueryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public const PER_PAGE = 25;

    use QueryHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return float|int|mixed|string
     */
    public function getUser(int $id)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin("user.departament", "departament")->addSelect("departament")
            ->leftJoin("user.city", "city")->addSelect("city")
            ->where('user.delete = :delete AND user.id = :id')
            ->setParameters(
                [
                    'delete'=>false,
                    'id' => $id
                ]
            );
        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        $roles = "'".implode("','", $result['0']['roles'])."'";

        $qb = $this->createQueryBuilder('user')
            ->from('App:Roles', 'role')
            ->where('role.roles_alt IN (:roles)')
            ->setParameter('roles', $roles);

        $query = $qb->getQuery();
        $role_result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        $result[0]['role'] = $role_result;

        $auth_list = [];
        if (!empty($result[0]['roles'])) {
            foreach ($result[0]['roles'] as $key=>$val)
            {
                if(!empty($val['auth_list'])) {
                    $auth_list_tmp = unserialize($val['auth_list']);
                    $auth_list = $auth_list + $auth_list_tmp;
                }
            }
        }

        return $result;
    }

    /**
     * @return float|int|mixed|string
     */
    public function getList(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $page = (empty($page) || $page === 1 || $page === 0) ? 0 : $page - 1;
        $first_result = (int)$page * (int)$on_page;
        $search = !empty($search) ? strtolower($search) : null;

        $order = $this->setSort($sort, 'user');

        $qb = $this->createQueryBuilder('user')
            ->orderBy($order[0], $order[1])
            ->leftJoin("user.departament", "departament")->addSelect("departament")
            ->leftJoin("user.city", "city")->addSelect("city")
            ->where('user.delete = :delete')
            ->setParameter('delete', false)
            ->setFirstResult($first_result)
            ->setMaxResults($on_page);

        if(!empty($search)) {
            $qb->andWhere("LOWER(user.fullname) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result;
    }

    public function getListAll(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('user');

        $qb->select('COUNT(user.id)')
            ->leftJoin("user.departament", "departament")
            ->leftJoin("user.city", "city")
            ->where('user.delete = :delete')
            ->setParameter('delete', false);

        if (!empty($search)) {
            $qb->andWhere("LOWER(user.fullname) LIKE :search ESCAPE '!'")
                ->setParameter('search', $this->makeLikeParam(mb_strtolower($search)));
        }

        $result = $qb->getQuery()
            ->execute(
                hydrationMode: Query::HYDRATE_ARRAY
            );

        return $result[0][1] ?? 0 ;
    }

    public function getCount(int|null $page = 0, int|null $on_page = 25, string|null $sort = null, string|null $search = null)
    {
        $qb = $this->createQueryBuilder('user');

        if(!empty($search)) {
            $qb->select('COUNT(user.id)')
                ->where("LOWER(user.fullname) LIKE :search ESCAPE '!'")
                ->andWhere('user.delete = :delete')
                ->setParameters(
                    [
                            'search' => $this->makeLikeParam(mb_strtolower($search)),
                            'delete' => false
                    ]
                );
        } else {
            $qb->select('COUNT(user.id)')
                ->where('user.delete = :delete')
                ->setParameters(
                    [
                        'delete' => false
                    ]
                );
        }

        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: Query::HYDRATE_ARRAY
        );

        return $result[0][1] ?? 0 ;
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
            $order = $prefix . '.username DESC';
        }

        return explode(' ', $order);
    }
}
