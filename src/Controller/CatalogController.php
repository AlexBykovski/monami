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
     * @Route("/", name="show_general_catalog")
     * @Route("/subcategories/{idGroup}", name="show_catalog_subcategories")
     *
     * @ParamConverter("group", class="App:ProductGroup", options={"id" = "idGroup"})
     */
    public function showSubcategoriesAction(Request $request, ProductGroup $group = null)
    {
        $parentGroups = [];

        if(!$group) {
            $parentGroups = $this->getDoctrine()->getRepository(ProductGroup::class)->findBy(["parentGroup" => null]);
        }

        return $this->render('client/catalog/subcategories.html.twig', [
            "group" => $group,
            "parentGroups" => $parentGroups
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
        $page = array_key_exists("page", $params) ? (int)$params["page"] : 1;

        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(
            ["productGroup" => $group],
            [$sort => $orderType],
            $count,
            ($page - 1) * $count
        );

        $fullCount = count($this->getDoctrine()->getRepository(Product::class)->findBy(
            ["productGroup" => $group]
        ));

        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($products as $product){
            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages
        ]);
    }

    /**
     * @Route("/search/products", name="search_catalog_products")
     */
    public function searchProductsAction(Request $request)
    {
        $params = $request->query->all();

        $text = $params["text"];

        if(!$text || strlen($text) < 2){
            return new JsonResponse([]);
        }

        $productsSearch = $this->getDoctrine()->getRepository(Product::class)->findByText($text);

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($productsSearch as $product){
            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse($parsedProducts);
    }

    /**
     * @Route("/search/results", name="search_catalog_products_results")
     */
    public function searchProductsResultsAction(Request $request)
    {
        $params = $request->query->all();

        $count = (int)$params["count"];
        $sort = $params["sort"];
        $text = $params["text"];
        $page = array_key_exists("page", $params) ? (int)$params["page"] : 1;

        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findByTextAndParams($count, $sort, $text, $page);

        $fullCount = $this->getDoctrine()->getRepository(Product::class)
            ->findCountByText($text);
        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($products as $product){
            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages
        ]);
    }

    /**
     * @Route("/show/search/results", name="show_search_catalog_products_results")
     */
    public function showSearchProductsResultsAction(Request $request)
    {
        $params = $request->query->all();

        $text = $params["text"];

        return $this->render('client/catalog/search-result.html.twig', [
            "text" => $text,
        ]);
    }
}