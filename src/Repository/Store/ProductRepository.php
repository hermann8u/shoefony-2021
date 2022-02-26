<?php

namespace App\Repository\Store;

use App\Entity\Store\Brand;
use App\Entity\Store\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOneWithDetails(int $id): ?Product
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->orderBy('c.createdAt', 'DESC')
        ;

        $this->addJoinComments($qb);
        $this->addJoinImage($qb);
        $this->addJoinColors($qb);
        $this->addJoinBrand($qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Product[]
     */
    public function findAllWithDetails(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
        ;

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Product[]
     */
    public function findProductsByBrand(Brand $brand): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.brand = :brand')
            ->setParameter('brand', $brand)
        ;

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Product[]
     */
    public function findLastCreatedProducts(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4)
        ;

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Product[]
     */
    public function findMostPopularProducts(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->groupBy('p', 'i')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->setMaxResults(4)
        ;

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    private function addJoinComments(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('c')
            ->leftJoin('p.comments', 'c')
        ;
    }

    private function addJoinColors(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('col')
            ->leftJoin('p.colors', 'col')
        ;
    }

    private function addJoinImage(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('i')
            ->innerJoin('p.image', 'i')
        ;
    }

    private function addJoinBrand(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('b')
            ->innerJoin('p.brand', 'b')
        ;
    }
}
