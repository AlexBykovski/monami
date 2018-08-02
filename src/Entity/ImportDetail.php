<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="import_detail")
 */
class ImportDetail
{
    const CODE_PRODUCTS = "products";
    const CODE_MANAGER = "manager";
    const CODE_CLIENTS = "clients";

    const STATUS_ERROR = "ERROR";
    const STATUS_SUCCESS = "SUCCESS";

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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $nameCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $imagesUrl;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $interval; //in minutes

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $lastUpdatedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $lastUpdateStatus;

    /**
     * ImportDetail constructor.
     */
    public function __construct()
    {
        $this->lastUpdatedAt = null;
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getImagesUrl(): string
    {
        return $this->imagesUrl;
    }

    /**
     * @param string $imagesUrl
     */
    public function setImagesUrl(string $imagesUrl): void
    {
        $this->imagesUrl = $imagesUrl;
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval(int $interval): void
    {
        $this->interval = $interval;
    }

    /**
     * @return DateTime|null
     */
    public function getLastUpdatedAt(): ?DateTime
    {
        return $this->lastUpdatedAt;
    }

    /**
     * @param DateTime|null $lastUpdatedAt
     */
    public function setLastUpdatedAt(?DateTime $lastUpdatedAt): void
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    /**
     * @return null|string
     */
    public function getLastUpdateStatus(): ?string
    {
        return $this->lastUpdateStatus;
    }

    /**
     * @param null|string $lastUpdateStatus
     */
    public function setLastUpdateStatus(?string $lastUpdateStatus): void
    {
        $this->lastUpdateStatus = $lastUpdateStatus;
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
    public function getNameCode(): string
    {
        return $this->nameCode;
    }

    /**
     * @param string $nameCode
     */
    public function setNameCode(string $nameCode): void
    {
        $this->nameCode = $nameCode;
    }
}