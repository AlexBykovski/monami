<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Product;
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

        $idProduct = $request->request->get("id");

        if(!$idProduct){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct) {
            $em->remove($basketProduct);
            $em->flush();
        }

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

        $idProduct = $request->request->get("id");
        $count = (int)$request->request->get("count");

        if(!$idProduct || !$count){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct) {
            $basketProduct->setCount($count);

            if($basketProduct->getCount() === 0){
                $em->remove($basketProduct);
                $em->flush();
            }
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

}