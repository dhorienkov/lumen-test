<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Repository;

use App\Entities\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Monolog\Logger;

/**
 * Class ProductRepository
 * Repository to query products
 *
 * @package App\Repository
 */
class ProductRepository
{
    /**
     * @var EntityRepository
     */
    private $genericRepository;

    public function __construct(ObjectRepository $genericRepository)
    {
        $this->genericRepository = $genericRepository;
    }

    /**
     * @param string|null $stockFilter
     * @param string|null $sortOnHand
     * @param bool $expand
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getProductsByCriteria(
        ?string $stockFilter = null,
        ?string $sortOnHand = null,
        bool $expand = false,
        int $limit = 0,
        int $offset = 100
    )
    {
        $query = $this->genericRepository
            ->createQueryBuilder('p')
            ->where('p.deletedAt = p.createdAt');

        // We can use Doctrine Criteria here instead of join
        if ($stockFilter || $sortOnHand || $expand){
            $query->leftJoin('p.stock', 's');
        }

        if ($expand) {
            $query->addSelect('s');
        }

        if ($stockFilter) {
            $query->andWhere('s.onHand = :onHand')
                ->setParameter('onHand', $stockFilter);
        }

        if ($sortOnHand) {
            $query->orderBy('s.onHand', $sortOnHand);
        }

        $query->setMaxResults($limit)->setFirstResult($offset);


        return $query->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return Product
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getDetailedProduct(int $id)
    {
        return $this->genericRepository
            ->createQueryBuilder('p')
            ->leftJoin('p.stock', 's')
            ->where('p.id = :id')
            ->andWhere('p.deletedAt = p.createdAt')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return object|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find(int $id)
    {
        return $this->genericRepository
            ->createQueryBuilder('p')
            ->where('p.id = :id')
            ->andWhere('p.deletedAt = p.createdAt')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
