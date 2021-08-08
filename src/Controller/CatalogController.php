<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductGroup;
use App\Entity\Purchase;
use FOS\UserBundle\Model\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

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
            'user' => $this->getUser(),
            'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
            'page' => isset($_GET['page']) ? $_GET['page'] : 1
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

        if (!$group) {
            $parentGroups = $this->getDoctrine()->getRepository(ProductGroup::class)->findBy(["parentGroup" => null]);
        }

        $ids = [];
        $childs = null;

        if ($group) {
            $childs = $group->getChildrenGroups();

            foreach ($childs as $child) {
                $ids[] = $child->getId();
            }

            $childs = implode(', ', $ids);
        } else {
            $childs = $this->getDoctrine()->getRepository(ProductGroup::class)->findAll();

            foreach ($childs as $child) {
                $ids[] = $child->getId();
            }

            $childs = implode(', ', $ids);
        }

        return $this->render('client/catalog/subcategories.html.twig', [
            "group" => $group,
            "parentGroups" => $parentGroups,
            'childIds' => $childs,
            'user' => $this->getUser(),
            'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
            'page' => isset($_GET['page']) ? $_GET['page'] : 1
        ]);
    }

    /**
     * @Route("/type/{type}", name="show_catalog_by_type")
     *
     */
    public function showCatalogTypeAction(Request $request, $type)
    {
        $productSales = [];
        $parentGroups = [];

        $ids = [];

        if ($type == 'new') {
            $groupName = 'Новинки';
        } elseif ($type == 'hit') {
            $groupName = 'Хиты';
        } else {
            $groupName = 'Акции';
        }

        if ($type == 'disc') {
            $childs = $this->getDoctrine()
                ->getRepository(ProductGroup::class)
                ->findByNot('sale', '0');
        } elseif ($type == 'new') {
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->findNew();

            $childs = $this->getDoctrine()->getRepository(ProductGroup::class)->findAll();

            $productIds = [];
            $productGroups = [];

            /** @var Purchase $product */
            foreach ($products as $product) {
                $productIds[] = $product->getId();
                if ($product->getProductGroup()) {
                    $productGroups[] = $product->getProductGroup()->getId();
                    $productSales[$product->getId()] = $product->getProductGroup()->getSale();
                }
            }

            /** @var ProductGroup $child */
            foreach ($childs as $child) {
                if (in_array($child->getId(), $productGroups)) {
                    $ids[] = $child->getId();
                }
            }

            $childs = implode(', ', $ids);

            return $this->render('client/catalog/type-new.html.twig', [
                "groupName" => $groupName,
                "parentGroups" => $parentGroups,
                'childIds' => $childs,
                'type' => $type,
                'productSales' => $productSales,
                'productIds' => $productIds,
                'user' => $this->getUser(),
                'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
                'page' => isset($_GET['page']) ? $_GET['page'] : 1
            ]);
        } elseif ($type == 'hit') {
            $childs = $this->getDoctrine()->getRepository(ProductGroup::class)->findAll();

            $productGroups = [$childs[0]->getId()];

            /** @var ProductGroup $child */
            foreach ($childs as $child) {
                if (in_array($child->getId(), $productGroups)) {
                    $ids[] = $child->getId();
                }
            }

            $childs = implode(', ', $ids);

            return $this->render('client/catalog/type-hit.html.twig', [
                "groupName" => $groupName,
                "parentGroups" => $parentGroups,
                'productSales' => $productSales,
                'childIds' => $childs,
                'type' => $type,
                'user' => $this->getUser(),
                'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
                'page' => isset($_GET['page']) ? $_GET['page'] : 1
            ]);
        } else {
            $childs = $childs = $this->getDoctrine()
                ->getRepository(ProductGroup::class)
                ->findByField('sale', '0');
        }

        foreach ($childs as $child) {
            $ids[] = $child->getId();
        }
        $childs = implode(', ', $ids);
        $user = $this->getUser();

        if (!$childs) {
            return $this->render('client/catalog/type-zero.html.twig', [
                "groupName" => $groupName,
                "parentGroups" => $parentGroups,
                'childIds' => 0,
                'type' => $type,
                'user' => $user,
                'page' => isset($_GET['page']) ? $_GET['page'] : 1
            ]);
        }

        if ($type == 'disc') {
            return $this->render('client/catalog/type-disc.html.twig', [
                "groupName" => $groupName,
                "parentGroups" => $parentGroups,
                'childIds' => $childs,
                'type' => $type,
                'user' => $user,
                'page' => isset($_GET['page']) ? $_GET['page'] : 1
            ]);
        }

        return $this->render('client/catalog/type-catalog.html.twig', [
            "groupName" => $groupName,
            "parentGroups" => $parentGroups,
            'childIds' => $childs,
            'type' => $type,
            'user' => $user,
            'page' => isset($_GET['page']) ? $_GET['page'] : 1,
            'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
        ]);
    }


    /**
     * @Route("/{idGroup}/filter", name="filter_catalog_subcategories")
     */
    public function filterSubcategoriesAction(Request $request, $idGroup)
    {
        $sales = $this
            ->getDoctrine()
            ->getRepository(ProductGroup::class)
            ->findAll();

        $salesGroups = [];

        foreach ($sales as $sale) {
            $salesGroups[$sale->getId()] = $sale->getSale();
        }

        if (!is_int(stripos($idGroup, ','))) {
            $group = $this
                ->getDoctrine()
                ->getRepository(ProductGroup::class)
                ->find($idGroup);
        } else {
            $idGroup = explode(', ', $idGroup);

            foreach ($idGroup as $id) {
                $group[] = $this
                    ->getDoctrine()
                    ->getRepository(ProductGroup::class)
                    ->find($id);
            }
        }

        $params = $request->query->all();

        $count = (int)$params["count"];
        $sort = $params["sort"];
        $orderType = $sort === "createdAt" ? "DESC" : "ASC";
        $page = array_key_exists("page", $params) ? (int)$params["page"] : 1;
        $sales = $this
            ->getDoctrine()
            ->getRepository(Purchase::class)
            ->findBy([], ['count' => 'DESC'], 100);
        $productIds = [];

        /** @var Purchase $product */
        foreach ($sales as $product) {
            if ($product->getProduct()->getLeftCount() > 0) {
                $productIds[] = $product->getProduct()->getId();
            }
        }

        $productIds = array_unique($productIds);

        if (is_array($idGroup)) {
            $req = implode(" OR p.productGroup = ", $idGroup);
        } else {
            $req = $idGroup;
        }

        if (isset($params['type']) && $params['type'] == 'hit') {
            $date = new DateTime("-1 month");
            $productIdsCounts = $this->getDoctrine()->getRepository(Product::class)
                ->findByHits($date,
                    $sort,
                    $orderType,
                    $count,
                    $page);

            $productHitsIds = [];

            foreach ($productIdsCounts as $prod) {
                $productHitsIds[] = $prod['id'];
            }

            $products = $this->getDoctrine()->getRepository(Product::class)
                ->findBy(['id' => $productHitsIds], [$sort => $orderType]);

        } elseif (isset($params['type']) && $params['type'] == 'new') {
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->findNew($sort, $orderType, $count, $page);

        } else {
            $products = $this->getDoctrine()->getRepository(Product::class)->findByDisc(
                $req,
                $sort,
                $orderType,
                $page,
                $count
            );
        }
        if (isset($params['type']) && $params['type'] == 'hit') {
            $fullCount = $this->getDoctrine()->getRepository(Product::class)->calcCountHits($date);
            $fullCount = $fullCount > 100 ? 100 : $fullCount;

        } elseif (isset($params['type']) && $params['type'] == 'new') {
            $fullCount = $this->getDoctrine()->getRepository(Product::class)->calcCountNew();
            $fullCount = $fullCount > 100 ? 100 : $fullCount;

        } elseif ((int)$idGroup == 0){
            $fullCount = 0;

        }else {
            $fullCount = $this->getDoctrine()->getRepository(Product::class)->calcCount($req);
        }

        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);

        if (((isset($params['type']) && $params['type'] == 'hit') || (isset($params['type']) && $params['type'] == 'new'))
            && (($page * $count) > 100))
        {
            $products = array_slice($products, 0, $count - ($page * $count - 100));
        }

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $productGroup = $product->getProductGroup()->getId();
            $product = $product->toArray();
            $product['sale'] = $salesGroups[$productGroup];
            $parsedProducts[] = $product;
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages,
            'test' => $request->getUri(),
            'page' => isset($_GET['page']) ? $_GET['page'] : 1
        ]);
    }

    /**
     * @Route("/search/products", name="search_catalog_products")
     */
    public function searchProductsAction(Request $request)
    {
        $params = $request->query->all();

        $sales = $this
            ->getDoctrine()
            ->getRepository(ProductGroup::class)
            ->findAll();

        $salesGroups = [];

        foreach ($sales as $sale) {
            $salesGroups[$sale->getId()] = $sale->getSale();
        }

        $text = $params["text"];

        if (!$text || strlen($text) < 2) {
            return new JsonResponse([]);
        }

        $productsSearch = $this->getDoctrine()->getRepository(Product::class)->findByText($text);

        /** @var Product $product */
        foreach ($productsSearch as $product) {
            if($product->getLeftCount() > 0) {
                $productGroup = $product->getProductGroup()->getId();
                $product = $product->toArray();
                $product['sale'] = $salesGroups[$productGroup];
                $parsedProducts[] = $product;
            }
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

        $sales = $this
            ->getDoctrine()
            ->getRepository(ProductGroup::class)
            ->findAll();

        $salesGroups = [];

        foreach ($sales as $sale) {
            $salesGroups[$sale->getId()] = $sale->getSale();
        }

        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findByTextAndParams($count, $sort, $text, $page);

        $fullCount = $this->getDoctrine()->getRepository(Product::class)
            ->findCountByText($text);
        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);

        $parsedProducts = [];

        /** @var Product $product */
        foreach ($products as $product) {
            if($product->getLeftCount() > 0) {
                $parsedProducts[] = $this->parseProduct($salesGroups, $product);
            }
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages,
            'user' => $this->getUser(),
            'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
            'page' => isset($_GET['page']) ? $_GET['page'] : 1
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
            'user' => $this->getUser(),
            'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
            'page' => isset($_GET['page']) ? $_GET['page'] : 1
        ]);
    }

    private function getSales()
    {
        $sales = $this
            ->getDoctrine()
            ->getRepository(ProductGroup::class)
            ->findAll();

        $salesGroups = [];

        foreach ($sales as $sale) {
            $salesGroups[$sale->getId()] = $sale->getSale();
        }

        return $salesGroups;
    }

    /**
     * @param $sales
     * @param Product $product
     * @return mixed
     */
    private function parseProduct($sales, $product)
    {
        $productGroup = $product->getProductGroup()->getId();
        $product = $product->toArray();
        $product['sale'] = $sales[$productGroup];

        return $product;
    }
}