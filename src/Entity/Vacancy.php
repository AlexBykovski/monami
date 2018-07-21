<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vacancy")
 */
class Vacancy
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
    private $title;

    /**
     * @var Collection
     *
     * One Vacancy has Many VacancyBlocks.
     * @ORM\OneToMany(targetEntity="VacancyBlock", mappedBy="vacancy", cascade={"persist", "remove"})
     */
    private $vacancyBlocks;

    /**
     * Vacancy constructor.
     */
    public function __construct()
    {
        $this->vacancyBlocks = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Collection
     */
    public function getVacancyBlocks(): Collection
    {
        return $this->vacancyBlocks;
    }

    /**
     * @param Collection $vacancyBlocks
     */
    public function setVacancyBlocks(Collection $vacancyBlocks): void
    {
        $this->vacancyBlocks = $vacancyBlocks;
    }
}