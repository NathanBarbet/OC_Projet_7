<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    public function findAllClients($usersid)
    {
      return $this->createQueryBuilder('c')
          ->select('c.id,c.name,c.firstname,c.email')
          ->where("c.user = $usersid")
          ->orderBy('c.id')
          ->getQuery()
          ->getResult();
    }

    public function findSingleClients($usersid, $clientsid)
    {
      return $this->createQueryBuilder('c')
          ->select('c.id,c.name,c.firstname,c.email,c.number,c.street,c.postalCode,c.city,c.tel')
          ->where("c.user = $usersid")
          ->andWhere("c.id = $clientsid")
          ->getQuery()
          ->getResult();
    }

    // /**
    //  * @return Clients[] Returns an array of Clients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clients
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
