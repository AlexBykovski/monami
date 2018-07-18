<?php

namespace App\Entity;


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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $salary;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $experience;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $skills;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $callbackEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $duties;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $demands;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $conditions;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $typeEmployment;

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
    public function getSalary(): string
    {
        return $this->salary;
    }

    /**
     * @param string $salary
     */
    public function setSalary(string $salary): void
    {
        $this->salary = $salary;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getExperience(): string
    {
        return $this->experience;
    }

    /**
     * @param string $experience
     */
    public function setExperience(string $experience): void
    {
        $this->experience = $experience;
    }

    /**
     * @return string
     */
    public function getSkills(): string
    {
        return $this->skills;
    }

    /**
     * @param string $skills
     */
    public function setSkills(string $skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @return string
     */
    public function getCallbackEmail(): string
    {
        return $this->callbackEmail;
    }

    /**
     * @param string $callbackEmail
     */
    public function setCallbackEmail(string $callbackEmail): void
    {
        $this->callbackEmail = $callbackEmail;
    }

    /**
     * @return string
     */
    public function getDuties(): string
    {
        return $this->duties;
    }

    /**
     * @param string $duties
     */
    public function setDuties(string $duties): void
    {
        $this->duties = $duties;
    }

    /**
     * @return string
     */
    public function getDemands(): string
    {
        return $this->demands;
    }

    /**
     * @param string $demands
     */
    public function setDemands(string $demands): void
    {
        $this->demands = $demands;
    }

    /**
     * @return string
     */
    public function getConditions(): string
    {
        return $this->conditions;
    }

    /**
     * @param string $conditions
     */
    public function setConditions(string $conditions): void
    {
        $this->conditions = $conditions;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getTypeEmployment(): string
    {
        return $this->typeEmployment;
    }

    /**
     * @param string $typeEmployment
     */
    public function setTypeEmployment(string $typeEmployment): void
    {
        $this->typeEmployment = $typeEmployment;
    }
}