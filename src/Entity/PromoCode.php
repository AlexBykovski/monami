<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("code", message="Этот код уже использовался.")
 *
 * @ORM\Entity
 * @ORM\Table(name="promo_code")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="code",
 *          column=@ORM\Column(
 *              name     = "code",
 *              length   = 191,
 *              unique   = true
 *          )
 *      )
 * })
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
	 * @var string|null
	 *
	 * @ORM\Column(type="string")
	 */
	private $code;

	/**
	 * @var float|null
	 *
	 * @ORM\Column(type="decimal", scale=2)
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
	 * @return null|string
	 */
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @param null|string $code
	 */
	public function setCode(?string $code): void
	{
		$this->code = $code;
	}

	/**
	 * @return float|null
	 */
	public function getDiscount(): ?float
	{
		return $this->discount;
	}

	/**
	 * @param float|null $discount
	 */
	public function setDiscount(?float $discount): void
	{
		$this->discount = $discount;
	}

	public function incUsages()
	{
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