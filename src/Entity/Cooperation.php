<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cooperation")
 */
class Cooperation
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
    private $schedule;

    /**
     * @var ArrayCollection
     *
     * One Cooperation has Many CooperationBlocks.
     *
     * @ORM\OneToMany(targetEntity="CooperationBlock", mappedBy="cooperation")
     */
    private $blocks;

    /**
     * Cooperation constructor.
     */
    public function __construct()
    {
        $this->blocks = new ArrayCollection();
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
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     */
    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    /**
     * @return ArrayCollection
     */
    public function getBlocks(): ArrayCollection
    {
        return $this->blocks;
    }

    /**
     * @param ArrayCollection $blocks
     */
    public function setBlocks(ArrayCollection $blocks): void
    {
        $this->blocks = $blocks;
    }
}