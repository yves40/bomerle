<?php

namespace App\Repository;

use App\Entity\Dblog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dblog>
 *
 * @method Dblog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dblog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dblog[]    findAll()
 * @method Dblog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DblogRepository extends ServiceEntityRepository
{
    const RETRIEVEDMAX = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dblog::class);
    }

    public function save(Dblog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dblog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Dblog[] Returns an array of Dblog objects
    */
    public function findByDateDesc(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.logtime', 'DESC')
            ->setMaxResults(DblogRepository::RETRIEVEDMAX)
            ->getQuery()
            ->getResult()
        ;
    }
   /**
    * @return Dblog[] Returns an array of Dblog objects
    */
    public function findByDateAsc(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.logtime', 'ASC')
            ->setMaxResults(DblogRepository::RETRIEVEDMAX)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getLogEntriesCount(): int
    {
        $query = $this->createQueryBuilder('s');
        $query->select('COUNT(s)');
        $logcount = $query->getQuery()->getSingleScalarResult();
        if ($logcount === null) $logcount = 0;
        return $logcount;
    }


//    public function findOneBySomeField($value): ?Dblog
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
