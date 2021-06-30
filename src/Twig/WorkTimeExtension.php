<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\AboutPage;
use Twig\TwigFunction;
use Doctrine\Common\Persistence\ManagerRegistry;

class WorkTimeExtension extends AbstractExtension
{
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_time_work', [$this, 'getTimeWork']),
        ];
    }

    public function getTimeWork()
    {
        $aboutPage = $this
            ->em
            ->getRepository(AboutPage::class)
            ->findAll();

        $parseAboutPage = [];

        foreach ($aboutPage as $column) {
            $parseAboutPage = $column;
        }
        return $parseAboutPage->getTimeWork();
    }
}