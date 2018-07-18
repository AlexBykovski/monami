<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_group")
 */
class ProductGroup
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
     * @var ArrayCollection
     *
     * One ProductGroup has Many Products.
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productGroup")
     */
    private $products;

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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $groupId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $photo;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * ProductGroup constructor.
     * @param string $apiId
     * @param string $simaCode
     * @param string $name
     * @param string $groupId
     * @param string $photo
     */
    public function __construct(string $apiId, string $simaCode, string $name, string $groupId, string $photo)
    {
        $this->apiId = $apiId;
        $this->simaCode = $simaCode;
        $this->name = $name;
        $this->groupId = $groupId;
        $this->photo = $photo;

        $this->products = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
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
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     */
    public function setGroupId(string $groupId): void
    {
        $this->groupId = $groupId;
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
}