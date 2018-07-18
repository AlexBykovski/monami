<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection
     *
     * One Manager has Many Clients.
     * @ORM\OneToMany(targetEntity="Client", mappedBy="manager")
     */
    private $clients;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
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
}