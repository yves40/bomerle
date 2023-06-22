<?php

namespace App\Repository;

use App\Entity\SlideShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SlideShow>
 *
 * @method SlideShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method SlideShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method SlideShow[]    findAll()
 * @method SlideShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlideShow::class);
    }

    public function save(SlideShow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SlideShow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDistinctSlideShows(): array
    {
        return $this->createQueryBuilder('s')
        ->select('distinct(s.name) as name')
        ->andWhere('s.active = 1')
        ->orderBy('s.name')
        ->getQuery()
        ->getResult();
    }   

//    /**
//     * @return SlideShow[] Returns an array of SlideShow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SlideShow
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
