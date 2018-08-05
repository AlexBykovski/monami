<?php

namespace App\Twig;

use App\Entity\ProductGroup;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Extension;
use Twig_Function;

class ProductGroupUrlExtension extends Twig_Extension
{
    private $urlGenerator;

    /**
     * ProductGroupUrlExtension constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('group_url', [$this, 'getProductGroupUrl']),
        );
    }

    public function getProductGroupUrl(ProductGroup $group)
    {
        if($group->getChildrenGroups()->count()){
            return $this->urlGenerator->generate("show_catalog_subcategories", ["idGroup" => $group->getId()]);
        }

        return $this->urlGenerator->generate("show_catalog", ["idGroup" => $group->getId()]);
    }
}