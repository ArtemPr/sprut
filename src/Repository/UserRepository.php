<?php

namespace App\Repository;

use App\Entity\User;
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

    const PER_PAGE = 25;

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
     * @param int $id
     * @return float|int|mixed|string
     */
    public function getUser(int $id)
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT user, dep, city
                FROM App\Entity\User user
                LEFT JOIN user.departament dep
                LEFT JOIN user.city city
                WHERE user.id = :id'
        )->setParameter('id', $id)->setMaxResults(self::PER_PAGE)->getResult(Query::HYDRATE_ARRAY);

        $roles = "'" . implode("','", $result['0']['roles']) . "'";
        $role_result = $entityManager->createQuery(
            "SELECT role
            FROM App\Entity\Roles role
            WHERE role.roles_alt IN (:roles)"
        )->setParameter('roles', $result['0']['roles'])->getResult(Query::HYDRATE_ARRAY);

        $result[0]['roles'] = $role_result;

        return $result;
    }

    /**
     * @return float|int|mixed|string
     */
    public function getList()
    {
        $entityManager = $this->getEntityManager();

        $result = $entityManager->createQuery(
            'SELECT user, dep, city
                FROM App\Entity\User user
                LEFT JOIN user.departament dep
                LEFT JOIN user.city city'
        )->setMaxResults(self::PER_PAGE)->getResult(Query::HYDRATE_ARRAY);
        return $result;
    }

}
