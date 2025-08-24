<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllProductByDci(int $dci): QueryBuilder
    {
        return $this->createQueryBuilder("p")
                        ->andWhere("p.dci = :dci")
                        ->setParameter("dci", $dci)
                        ->orderBy("p.name", "ASC");
    }

    public function findAllProduct(): QueryBuilder
    {
        return $this->createQueryBuilder("p")
                        ->orderBy("p.name", "ASC");
    }

    public function findAllProductSearch(string $filter): QueryBuilder
    {
        return $this->createQueryBuilder("p")
                        ->andWhere('p.name LIKE :filter OR p.description LIKE :filter')
                        ->setParameter('filter', '%'.$filter.'%')
                        ->orderBy("p.name", "ASC");
    }
}
