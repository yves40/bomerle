<?php

namespace App\Repository;

use App\Entity\SlideShow;
use App\Entity\SlideImages;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SlideImages>
 *
 * @method SlideImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method SlideImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method SlideImages[]    findAll()
 * @method SlideImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlideImages::class);
    }

    public function save(SlideImages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SlideImages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // To get the knife image upper rank when adding images
    public function getMaxRank(SlideShow $slideshow): int
    {
        $query = $this->createQueryBuilder('s');
        $query->select('MAX(s.rank)');
        $query->where('s.slideshow = :val')->setParameter('val', $slideshow);
        $maxrank = $query->getQuery()->getSingleScalarResult();
        if ($maxrank === null) $maxrank = 0;
        return $maxrank;
    }

    public function findSlideshowImagesByRank($slideshow): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.slideshow = :val')
            ->setParameter('val', $slideshow)
            ->orderBy('i.rank', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
//    /**
//     * @return SlideImages[] Returns an array of SlideImages objects
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

//    public function findOneBySomeField($value): ?SlideImages
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
