<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findAllWish(): array
    {
        $dql = "SELECT w FROM App\Entity\Wish AS w";
        return $this->_em->createQuery($dql)
            ->execute();

    }

    public function findWish(int $id): Wish
    {
        $dql = "SELECT w FROM App\Entity\Wish AS w WHERE id = :id";
        return $this->_em->createQuery($dql)
            ->execute();
    }

    public function findPublishedWishesWithCategories(): ?array
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->join('w.ref_category', 'c')
            ->addSelect('c');
        $queryBuilder->andWhere('w.isPublished = 1');
        $queryBuilder->orderBy('w.dateCreated', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

//    /**
//     * @return Wish[] Returns an array of Wish objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Wish
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
