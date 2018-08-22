<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="basket")
 */
class Basket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var PromoCode|null
     *
     * One Basket has One PromoCode.
     * @ORM\OneToOne(targetEntity="PromoCode")
     * @ORM\JoinColumn(name="promocode_id", nullable=true, referencedColumnName="id")
     */
    private $promoCode;

    /**
     * @var ArrayCollection
     *
     * One Basket has Many BasketProducts.
     * @ORM\OneToMany(targetEntity="BasketProduct", mappedBy="basket")
     */
    private $basketProducts;

    /**
     * @var Client
     *
     * One Basket has One Client.
     * @ORM\OneToOne(targetEntity="Client", inversedBy="basket")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * Basket constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->basketProducts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return PromoCode|null
     */
    public function getPromoCode(): ?PromoCode
    {
        return $this->promoCode;
    }

    /**
     * @param PromoCode|null $promoCode
     */
    public function setPromoCode(?PromoCode $promoCode): void
    {
        $this->promoCode = $promoCode;
    }

    /**
     * @return ArrayCollection
     */
    public function getBasketProducts(): ArrayCollection
    {
        return $this->basketProducts;
    }

    /**
     * @param ArrayCollection $basketProducts
     */
    public function setBasketProducts(ArrayCollection $basketProducts): void
    {
        $this->basketProducts = $basketProducts;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function toArray()
    {
        $products = [];
        $sum = 0;

        /** @var BasketProduct $basketProduct */
        foreach ($this->basketProducts as $basketProduct){
            $sum += $basketProduct->getCount() * $basketProduct->getProduct()->getCost();

            $products[(string)$basketProduct->getProduct()->getId()] = $basketProduct->getCount();
        }

        return [
            "products" => $products,
            "sum" => $sum,
        ];
    }

    public function getBasketProductById($id)
    {
        /** @var BasketProduct $basketProduct */
        foreach ($this->basketProducts as $basketProduct){
            if((int)$id  === (int)$basketProduct->getProduct()->getId()){
                return $basketProduct;
            }
        }

        return null;
    }
}