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
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $contacts;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $requisites;

    /**
     * @var string
     * @ORM\Column(type="text")
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
     * @return string
     */
    public function getContacts(): string
    {
        return $this->contacts;
    }

    /**
     * @param string $contacts
     */
    public function setContacts(string $contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @return string
     */
    public function getRequisites(): string
    {
        return $this->requisites;
    }

    /**
     * @param string $requisites
     */
    public function setRequisites(string $requisites): void
    {
        $this->requisites = $requisites;
    }

    /**
     * @return string
     */
    public function getMap(): string
    {
        return $this->map;
    }

    /**
     * @param string $map
     */
    public function setMap(string $map): void
    {
        $this->map = $map;
    }
}