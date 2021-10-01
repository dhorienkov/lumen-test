<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Services;


use App\Entities\Product;
use Doctrine\ORM\EntityManager;

class ProductManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ProductManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function create(Product $product)
    {
        try {
            \EntityManager::persist($product);
        } catch(\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage());
        }

        \EntityManager::flush();

        return $product;
    }

    /**
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data)
    {
        if($data['code']) {
            $product->updateCode($data['code']);
        }

        if($data['name']) {
            $product->updateName($data['name']);
        }

        if($data['description']) {
            $product->updateDescription($data['description']);
        }

        try {
            \EntityManager::persist($product);
        } catch(\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage());
        }

        \EntityManager::flush();

        return $product;
    }
}
