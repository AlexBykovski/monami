<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("login", message="Этот login уже зарегистрирован.")
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({"ROLE_CLIENT" = "Client", "ROLE_ADMIN" = "Admin"})
 */
abstract class User extends BaseUser
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_CLIENT = "ROLE_CLIENT";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

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
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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
}