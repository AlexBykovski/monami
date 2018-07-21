<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page")
 */
class MainPage
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
     * @var Collection
     *
     * Many MainPage have Many LinkImages.
     * @ORM\ManyToMany(targetEntity="LinkImage", cascade={"persist"})
     * @ORM\JoinTable(name="main_page_link_images",
     *      joinColumns={@ORM\JoinColumn(name="main_page", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="link_image", referencedColumnName="id", unique=true)}
     *      )
     */
    private $linkImages;

    /**
     * MainPage constructor.
     */
    public function __construct()
    {
        $this->linkImages = new ArrayCollection();
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
     * @return Collection
     */
    public function getLinkImages(): Collection
    {
        return $this->linkImages;
    }

    /**
     * @param Collection $linkImages
     */
    public function setLinkImages(Collection $linkImages): void
    {
        $this->linkImages = $linkImages;
    }
}