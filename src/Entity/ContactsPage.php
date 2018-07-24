<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contacts_page")
 */
class ContactsPage
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $contacts;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $requisites;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $map;

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
    public function getContacts(): ?string
    {
        return $this->contacts;
    }

    /**
     * @param null|string $contacts
     */
    public function setContacts(?string $contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @return null|string
     */
    public function getRequisites(): ?string
    {
        return $this->requisites;
    }

    /**
     * @param null|string $requisites
     */
    public function setRequisites(?string $requisites): void
    {
        $this->requisites = $requisites;
    }

    /**
     * @return null|string
     */
    public function getMap(): ?string
    {
        return $this->map;
    }

    /**
     * @param null|string $map
     */
    public function setMap(?string $map): void
    {
        $this->map = $map;
    }
}