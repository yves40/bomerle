<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Knifes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Images>
 *
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Images::class);
    }

    public function save(Images $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Images $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // To get the knife image upper rank when adding images
    public function getMaxRankForKnifeImage(Knifes $knife): int
    {
        $query = $this->createQueryBuilder('s');
        $query->select('MAX(s.rank)');
        $query->where('s.knifes = :val')->setParameter('val', $knife);
        $maxrank = $query->getQuery()->getSingleScalarResult();
        if ($maxrank === null) $maxrank = 0;
        return $maxrank;
    }

    public function findKnifeImagesByRank($knife): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.knifes = :val')
            ->setParameter('val', $knife)
            ->orderBy('i.rank', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findImage($name) : array {
        return $this->createQueryBuilder('i')
            ->andWhere('i.filename = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Images[] Returns an array of Images objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Images
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
