<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client extends User
{
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
     * @var ArrayCollection
     *
     * One Product has Many Purchases.
     * @ORM\OneToMany(targetEntity="Purchase", mappedBy="client", cascade={"remove"})
     */
    private $purchases;

    /**
     * @var Basket
     *
     * One Client has One Basket.
     * @ORM\OneToOne(targetEntity="Basket", mappedBy="client", cascade={"persist", "remove"})
     */
    private $basket;

    /**
     * Client constructor.
     * @param string $apiId
     * @param string $phone
     * @param string $email
     * @param string $login
     * @param Manager|null $manager
     * @param null|string $contrAgent
     */
    public function __construct(string $apiId, string $phone, string $email, string $login, ?Manager $manager, ?string $contrAgent)
    {
        parent::__construct();

        $this->apiId = $apiId;
        $this->phone = $phone;
        $this->email = $email;
        $this->username = $login;
        $this->contrAgent = $contrAgent;
        // to prevent unique constraints
        $this->emailCanonical = $login;
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

    /**
     * @return Basket
     */
    public function getBasket(): Basket
    {
        return $this->basket;
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }
}