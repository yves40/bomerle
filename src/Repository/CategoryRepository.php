<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listCategories($new, $id): array
    {
        // Creating a new one or modifying an existing ? 
        if($new) {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id <> :id')
                ->setParameter('id', $id)
                ->orderBy('c.rank, c.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
        else {
            return $this->createQueryBuilder('c')
                ->orderBy('c.rank, c.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }
    /**
     * Jointure entre Category & knifes
     * C'est le join qui fait tout le boulot
     */
    public function findDistinctActiveCategories(): array
    {
        return $this->createQueryBuilder('c')
            ->select('distinct(c.name) as catname, c.id as catid, 
                            c.image as catimage, 
                            c.rank,
                            c.description as catdesc')
            ->join('c.knifes', 'k')
            ->orderBy('c.rank')
            ->getQuery()
            ->getResult();
    }   

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
