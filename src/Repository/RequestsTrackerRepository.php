<?php

namespace App\Repository;

use App\Core\Token;
use App\Entity\RequestsTracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestsTracker>
 *
 * @method RequestsTracker|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestsTracker|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestsTracker[]    findAll()
 * @method RequestsTracker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestsTrackerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestsTracker::class);
    }

    public function save(RequestsTracker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RequestsTracker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   public function findRequestBySelector($selector, 
                    int $status = RequestsTracker::STATUS_REQUESTED): ?RequestsTracker
   {
       return $this->createQueryBuilder('r')
            ->andWhere('r.selector = :val0')
            ->andWhere('r.status = :val1')
            ->setParameter('val0', $selector)
            ->setParameter('val1', $status )
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }


//    /**
//     * @return RequestsTracker[] Returns an array of RequestsTracker objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RequestsTracker
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
