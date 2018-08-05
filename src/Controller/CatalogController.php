<?php

namespace App\Controller;

use App\Entity\ProductGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/catalog")
 */
class CatalogController extends Controller
{
    /**
     * @Route("/{idGroup}", name="show_catalog")
     *
     * @ParamConverter("group", class="App:ProductGroup", options={"id" = "idGroup"})
     */
    public function showCatalogAction(Request $request, ProductGroup $group)
    {
        return $this->render('client/catalog/catalog.html.twig', [
            "group" => $group,
        ]);
    }

    /**
     * @Route("/subcategories/{idGroup} ", name="show_catalog_subcategories")
     *
     * @ParamConverter("group", class="App:ProductGroup", options={"id" = "idGroup"})
     */
    public function showSubcategoriesAction(Request $request, ProductGroup $group)
    {
        return $this->render('client/catalog/subcategories.html.twig', [
            "group" => $group,
        ]);
    }
}