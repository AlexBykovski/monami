<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="promo_code")
 */
class PromoCode
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
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $discount;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isUsed = false;

    /**
     * PromoCode constructor.
     */
    public function __construct()
    {
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
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
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    /**
     * @param bool $isUsed
     */
    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }
}