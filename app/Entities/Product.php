<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Stock", mappedBy="product", fetch="LAZY")
     * @var Stock
     */
    private $stock;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deletedAt;

    /**
     * Product constructor.
     * @param string $code
     * @param string $name
     * @param string $description
     * @throws \Exception
     */
    public function __construct(
        string $code,
        string $name,
        string $description
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->deletedAt = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getStock()
    {
        return $this->stock;
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
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }


    public function setDeleted()
    {
        $this->deletedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @param string $code
     * @return $this
     * @throws \Exception
     */
    public function updateCode(string $code)
    {
        $this->code = $code;
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @param string $descritpion
     * @return $this
     * @throws \Exception
     */
    public function updateDescription(string $descritpion)
    {
        $this->description = $descritpion;
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function updateName(string $name)
    {
        $this->name = $name;
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public static function fromArray(array $data)
    {
        $code = $data['code'] ?? null;
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;

        return new self($code, $name, $description);
    }
}
