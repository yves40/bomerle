<?php

namespace App\Repository;

use App\Entity\Knifes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Knifes>
 *
 * @method Knifes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Knifes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Knifes[]    findAll()
 * @method Knifes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnifesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Knifes::class);
    }

    public function save(Knifes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Knifes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findPublished(): array
    {
        return $this->createQueryBuilder('s')
        ->select('distinct(s.name) as name')
        ->andWhere('s.published = true')
        ->orderBy('s.name')
        ->getQuery()
        ->getResult();
    }   

//    /**
//     * @return Knifes[] Returns an array of Knifes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Knifes
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
