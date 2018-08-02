<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="manager")
 */
class Manager
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullName;

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
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var Collection
     *
     * One Manager has Many Clients.
     * @ORM\OneToMany(targetEntity="Client", mappedBy="manager")
     */
    private $clients;

    /**
     * Manager constructor.
     * @param null|string $image
     * @param null|string $fullName
     * @param string $apiId
     * @param string $phone
     * @param string $email
     */
    public function __construct(?string $image, ?string $fullName, string $apiId, string $phone, string $email)
    {
        $this->image = $image;
        $this->fullName = $fullName;
        $this->apiId = $apiId;
        $this->phone = $phone;
        $this->email = $email;

        $this->clients = new ArrayCollection();
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
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param null|string $fullName
     */
    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return ArrayCollection
     */
    public function getClients(): ArrayCollection
    {
        return $this->clients;
    }

    /**
     * @param ArrayCollection $clients
     */
    public function setClients(ArrayCollection $clients): void
    {
        $this->clients = $clients;
    }

    /**
     * @param Client $client
     */
    public function addClient(Client $client): void
    {
        $this->clients->add($client);
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
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}