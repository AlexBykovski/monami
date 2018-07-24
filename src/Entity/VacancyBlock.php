<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vacancy_block")
 */
class VacancyBlock
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
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Vacancy
     *
     * Many VacancyBlocks have One Vacancy.
     * @ORM\ManyToOne(targetEntity="Vacancy", inversedBy="vacancyBlocks"))
     * @ORM\JoinColumn(name="vacancy", referencedColumnName="id")
     */
    private $vacancy;

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
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Vacancy
     */
    public function getVacancy(): Vacancy
    {
        return $this->vacancy;
    }

    /**
     * @param Vacancy $vacancy
     */
    public function setVacancy(Vacancy $vacancy): void
    {
        $this->vacancy = $vacancy;
    }
}