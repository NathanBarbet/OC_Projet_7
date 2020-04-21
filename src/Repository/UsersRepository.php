<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function findAllUsers($clientid, $page, $limit)
    {
      return $this->createQueryBuilder('u')
          ->select("u.id,u.name,u.firstname,u.email")
          ->where("u.client = $clientid")
          ->orderBy('u.id')
          ->getQuery()
          ->setFirstResult(($page - 1) * $limit)
          ->setMaxResults($limit)
          ->getResult();
    }

    public function findSingleUsers($clientid, $usersid)
    {
      return $this->createQueryBuilder('u')
          ->select("u.id,u.name,u.firstname,u.email,u.number,u.street,u.postalCode,u.city,u.tel")
          ->where("u.client = $clientid")
          ->andWhere("u.id = $usersid")
          ->getQuery()
          ->getResult();
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
