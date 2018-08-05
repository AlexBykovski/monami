<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Collection
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
    private $photo;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Collection
     *
     * One ProductGroup has Many ProductGroups.
     * @ORM\OneToMany(targetEntity="ProductGroup", mappedBy="parentGroup")
     */
    private $childrenGroups;

    /**
     * @var ProductGroup|null
     *
     * Many ProductGroups have One ProductGroup.
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="childrenGroups")
     * @ORM\JoinColumn(name="parent_group", referencedColumnName="id")
     */
    private $parentGroup;

    /**
     * ProductGroup constructor.
     * @param string $apiId
     * @param string $simaCode
     * @param string $name
     * @param string $photo
     */
    public function __construct(string $apiId, string $simaCode, string $name, string $photo)
    {
        $this->apiId = $apiId;
        $this->simaCode = $simaCode;
        $this->name = $name;
        $this->photo = $photo;

        $this->products = new ArrayCollection();
        $this->childrenGroups = new ArrayCollection();
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
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Collection $products
     */
    public function setProducts(Collection $products): void
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

    /**
     * @return Collection
     */
    public function getChildrenGroups(): Collection
    {
        return $this->childrenGroups;
    }

    /**
     * @param Collection $childrenGroups
     */
    public function setChildrenGroups(Collection $childrenGroups): void
    {
        $this->childrenGroups = $childrenGroups;
    }

    /**
     * @return ProductGroup|null
     */
    public function getParentGroup(): ?ProductGroup
    {
        return $this->parentGroup;
    }

    /**
     * @param ProductGroup|null $parentGroup
     */
    public function setParentGroup(?ProductGroup $parentGroup): void
    {
        $this->parentGroup = $parentGroup;
    }
}