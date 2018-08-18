<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/subcategories/{idGroup}", name="show_catalog_subcategories")
     *
     * @ParamConverter("group", class="App:ProductGroup", options={"id" = "idGroup"})
     */
    public function showSubcategoriesAction(Request $request, ProductGroup $group)
    {
        return $this->render('client/catalog/subcategories.html.twig', [
            "group" => $group,
        ]);
    }

    /**
     * @Route("/{idGroup}/filter", name="filter_catalog_subcategories")
     *
     * @ParamConverter("group", class="App:ProductGroup", options={"id" = "idGroup"})
     */
    public function filterSubcategoriesAction(Request $request, ProductGroup $group)
    {
        $params = $request->query->all();

        $count = (int)$params["count"];
        $sort = $params["sort"];
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";

        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(
            ["productGroup" => $group],
            [$sort => $orderType],
            $count
        );

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($products as $product){
            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse($parsedProducts);
    }
}