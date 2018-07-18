<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $apiId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $simaCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var ProductGroup
     *
     * Many Product have One ProductGroup.
     *
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="products")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $productGroup;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $photo;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" = 0})
     */
    private $leftCount = 0;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     *
     * One Product has Many Purchases.
     * @ORM\OneToMany(targetEntity="Purchase", mappedBy="product", cascade={"remove"})
     */
    private $purchases;

    /**
     * Product constructor.
     * @param string $apiId
     * @param string $simaCode
     * @param string $name
     * @param ProductGroup $productGroup
     * @param string $photo
     * @param float $cost
     * @param int $leftCount
     */
    public function __construct(string $apiId, string $simaCode, string $name, ProductGroup $productGroup, string $photo, float $cost, int $leftCount)
    {
        $this->apiId = $apiId;
        $this->simaCode = $simaCode;
        $this->name = $name;
        $this->productGroup = $productGroup;
        $this->photo = $photo;
        $this->cost = $cost;
        $this->leftCount = $leftCount;

        $this->purchases = new ArrayCollection();
        $this->createdAt = new DateTime();
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
     * @return string
     */
    public function getApiId(): string
    {
        return $this->apiId;
    }

    /**
     * @param string $apiId
     */
    public function setApiId(string $apiId): void
    {
        $this->apiId = $apiId;
    }

    /**
     * @return string
     */
    public function getSimaCode(): string
    {
        return $this->simaCode;
    }

    /**
     * @param string $simaCode
     */
    public function setSimaCode(string $simaCode): void
    {
        $this->simaCode = $simaCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ProductGroup
     */
    public function getProductGroup(): ProductGroup
    {
        return $this->productGroup;
    }

    /**
     * @param ProductGroup $productGroup
     */
    public function setProductGroup(ProductGroup $productGroup): void
    {
        $this->productGroup = $productGroup;
    }

    /**
     * @return string
     */
    public function getPhoto(): string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getLeftCount(): int
    {
        return $this->leftCount;
    }

    /**
     * @param int $leftCount
     */
    public function setLeftCount(int $leftCount): void
    {
        $this->leftCount = $leftCount;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getPurchases(): ArrayCollection
    {
        return $this->purchases;
    }

    /**
     * @param ArrayCollection $purchases
     */
    public function setPurchases(ArrayCollection $purchases): void
    {
        $this->purchases = $purchases;
    }
}