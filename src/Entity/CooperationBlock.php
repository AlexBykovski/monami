<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cooperation_block")
 */
class CooperationBlock
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
     * @var Cooperation
     *
     * Many CooperationBlock have One Cooperation.
     * @ORM\ManyToOne(targetEntity="Cooperation", inversedBy="blocks")
     * @ORM\JoinColumn(name="cooperation", referencedColumnName="id")
     */
    private $cooperation;

    /**
     * @return null|int
     */
    public function getId(): ?int
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
     * @return Cooperation
     */
    public function getCooperation(): Cooperation
    {
        return $this->cooperation;
    }

    /**
     * @param Cooperation $cooperation
     */
    public function setCooperation(Cooperation $cooperation): void
    {
        $this->cooperation = $cooperation;
    }
}