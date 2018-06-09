<?php

namespace App\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client extends User
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $apiId;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Manager|null
     *
     * Many Clients have One Manager.
     * @ORM\ManyToOne(targetEntity="Manager", inversedBy="clients")
     * @ORM\JoinColumn(name="manager", referencedColumnName="id")
     */
    private $manager;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $contrAgent;

    /**
     * @ORM\Column(type="string")
     */
    private $purchaseHistories; //@@todo need Entity

    /**
     * @ORM\Column(type="string")
     */
    private $basket; //@@todo need Entity

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
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
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Manager|null
     */
    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    /**
     * @param Manager|null $manager
     */
    public function setManager(?Manager $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return null|string
     */
    public function getContrAgent(): ?string
    {
        return $this->contrAgent;
    }

    /**
     * @param null|string $contrAgent
     */
    public function setContrAgent(?string $contrAgent): void
    {
        $this->contrAgent = $contrAgent;
    }
}