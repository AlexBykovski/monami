<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection
     *
     * Many MainPage have Many linkImages.
     * @ORM\ManyToMany(targetEntity="LinkImage")
     * @ORM\JoinTable(name="main_page_link_images",
     *      joinColumns={@ORM\JoinColumn(name="main_page", referencedColumnName="id", unique=true)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="link_image", referencedColumnName="id")}
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
     * @return ArrayCollection
     */
    public function getLinkImages(): ArrayCollection
    {
        return $this->linkImages;
    }

    /**
     * @param ArrayCollection $linkImages
     */
    public function setLinkImages(ArrayCollection $linkImages): void
    {
        $this->linkImages = $linkImages;
    }
}