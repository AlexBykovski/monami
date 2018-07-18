<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="basket_product")
 */
class BasketProduct
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @var Basket
     *
     * Many BasketProducts have One Basket.
     * @ORM\ManyToOne(targetEntity="Basket", inversedBy="basketProducts")
     * @ORM\JoinColumn(name="basket_id", referencedColumnName="id")
     */
    private $basket;

    /**
     * BasketProduct constructor.
     * @param Product $product
     * @param Basket $basket
     * @param int $count
     */
    public function __construct(Product $product, Basket $basket, int $count = 0)
    {
        $this->product = $product;
        $this->count = $count;
        $this->basket = $basket;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }
}