<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalog")
 */
class CatalogController extends Controller
{
    /**
     * @Route("/", name="show_catalog")
     */
    public function showCatalogAction(Request $request)
    {
        return $this->render('client/catalog/catalog.html.twig', []);
    }

    /**
     * @Route("/subcategories", name="show_catalog_subcategories")
     */
    public function showSubcategoriesAction(Request $request)
    {
        return $this->render('client/catalog/subcategories.html.twig', []);
    }
}