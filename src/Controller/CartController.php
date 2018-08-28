<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
use App\Entity\PromoCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/cart")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class CartController extends Controller
{
    /**
     * @Route("/add-product-to-cart", name="add_product_to_cart")
     */
    public function addProductToCartAction(Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->getUser()->getBasket();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = (int)$content["count"];

        if(!$count || !$idProduct){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct){
            $basketProduct->setCount($basketProduct->getCount() + $count);
        }
        else{
            $newBasketProduct = new BasketProduct($product, $basket, $count);

            $em->persist($newBasketProduct);
        }

        $product->removeCount($count);

        $em->flush();
        $em->refresh($basket);

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/remove-product-from-cart", name="remove_product_from_cart")
     */
    public function removeProductFromCartAction(Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->getUser()->getBasket();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];

        if(!$idProduct){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct) {
            $product->addCount($basketProduct->getCount());

            $em->remove($basketProduct);
            $em->flush();
        }

        $em->refresh($basket);

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/change-count-product-in-cart", name="change_count_product_in_cart")
     */
    public function changeCountProductInCartAction(Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->getUser()->getBasket();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = (int)$content["count"];

        if(!$idProduct || !$count){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct) {
            $product->addCount($basketProduct->getCount());
            $basketProduct->setCount($count);
            $product->addCount($basketProduct->getCount());

            if($basketProduct->getCount() === 0){
                $em->remove($basketProduct);
                $em->flush();
            }
        }

        $em->refresh($basket);

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/use-promocode", name="use_promocode")
     */
    public function usePromoCodeAction(Request $request)
    {
        /** @var Basket $basket */
        $basket = $this->getUser()->getBasket();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $code = $content["code"];

        if(!$code){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $promoCode = $em->getRepository(PromoCode::class)->findOneBy(["code" => $code]);

        if(!($promoCode instanceof PromoCode) || $promoCode->isUsed()){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basket->setPromoCode($promoCode);
        $promoCode->setIsUsed(true);

        $em->flush();
        $em->refresh($basket);

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/filter", name="filter_cart_products")
     */
    public function filterSubcategoriesAction(Request $request)
    {
        $params = $request->query->all();

        $count = (int)$params["count"];

        $products = $this->getDoctrine()->getRepository(BasketProduct::class)->findBy(
            ["basket" => $this->getUser()->getBasket()],
            null,
            $count
        );

        $parsedProducts = [];

        /** @var BasketProduct $product */
        foreach ($products as $product){
            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse($parsedProducts);
    }
}