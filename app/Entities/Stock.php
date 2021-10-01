<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stock")
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Product", inversedBy="stock")
     * @var Product
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $onHand;

    /**
     * @ORM\Column(type="integer")
     */
    private $taken;

    /**
     * @ORM\Column(type="datetime")
     */
    private $productionDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Product constructor.
     * @param Product $product
     * @param int $onHand
     * @param \DateTime $productionDate
     * @param int $taken
     * @throws \Exception
     */
    public function __construct(
        Product $product,
        int $onHand,
        \DateTime $productionDate,
        int $taken = 0
    )
    {
        $this->product = $product;
        $this->onHand = $onHand;
        $this->taken = $taken;
        $this->productionDate = $productionDate;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return mixed
     */
    public function getOnHand()
    {
        return $this->onHand;
    }

    /**
     * @return mixed
     */
    public function getTaken()
    {
        return $this->taken;
    }

    /**
     * @return mixed
     */
    public function getProductionDate()
    {
        return $this->productionDate;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param int $onHand
     */
    public function updateOnHand(int $onHand)
    {
        $this->onHand = $onHand;
    }

    /**
     * @param \Datetime $productionDate
     */
    public function updateProductionDate(\Datetime $productionDate)
    {
        $this->productionDate = $productionDate;
    }
}
