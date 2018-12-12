<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Client;
use App\Entity\Product;
use App\Entity\PromoCode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/cart")
 *
 */
class CartController extends Controller
{
    /**
     * @Route("/add-product-to-cart", name="add_product_to_cart")
     */
    public function addProductToCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = (int)$content["count"];

        if(!$count || !$idProduct){
            return new JsonResponse([
                "cart" => $basket->toArray(),
                "text" => "Ошибка при добавлении товара",
                "type" => "warn",
            ]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if(!($product instanceof Product)){
            return new JsonResponse([
                "cart" => $basket->toArray(),
                "text" => "Ошибка при добавлении товара",
                "type" => "warn",
            ]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if($basketProduct instanceof BasketProduct){
            $basketProduct->setCount($basketProduct->getCount() + $count);
        }
        else{
            $newBasketProduct = new BasketProduct($product, $basket, $count);

            if($user) {
                $em->persist($newBasketProduct);
            }

            $basketProducts = $basket->getBasketProducts();
            $basketProducts->add($newBasketProduct);
            $basket->setBasketProducts($basketProducts);
        }

        $product->removeCount($count);

        $em->flush();

        if($user) {
            $em->refresh($basket);
        }

        return new JsonResponse([
            "cart" => $basket->toArray(),
            "text" => "Добавлено в корзину: " . $product->getName() . " " . $count . " ед.",
            "type" => "info",
        ]);
    }

    /**
     * @Route("/remove-product-from-cart", name="remove_product_from_cart")
     */
    public function removeProductFromCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
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

            if($user) {
                $em->remove($basketProduct);
                $em->flush();
            }
            else{
                $basketProducts = new ArrayCollection();

                /** @var BasketProduct $basketProductCookie */
                foreach ($basket->getBasketProducts() as $basketProductCookie){
                    if($basketProductCookie->getProduct()->getId() !== $idProduct){
                        $basketProducts->add($basketProductCookie);
                    }
                }

                $basket->setBasketProducts($basketProducts);
            }
        }

        if($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/change-count-product-in-cart", name="change_count_product_in_cart")
     */
    public function changeCountProductInCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
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
            $absDiff = abs($basketProduct->getCount() - $count);

            if($basketProduct->getCount() > $count){
                $product->removeCount($absDiff);
            }
            else{
                $product->addCount($absDiff);
            }

            $basketProduct->setCount($count);

            if($basketProduct->getCount() === 0 && $user){
                $em->remove($basketProduct);
            }

            $basketProducts = new ArrayCollection();

            /** @var BasketProduct $basketProductCookie */
            foreach ($basket->getBasketProducts() as $basketProductCookie){
                if($basketProductCookie->getProduct()->getId() === $idProduct){
                    $basketProducts->add($basketProductCookie);
                }
            }

            $basketProducts->add($basketProduct);

            $basket->setBasketProducts($basketProducts);

            $em->flush();
        }

        if($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/use-promocode", name="use_promocode")
     */
    public function usePromoCodeAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
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

        if($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/filter", name="filter_cart_products")
     */
    public function filterSubcategoriesAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);

        $params = $request->query->all();

        $count = (int)$params["count"];
        $sort = $params["sort"];
        $page = array_key_exists("page", $params) ? (int)$params["page"] : 1;
        $allBasketProducts = $basket->getBasketProducts($sort);
        $fullCount =  $allBasketProducts->count();
        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);
        $parsedProducts = [];

        $basketProducts = array_slice($allBasketProducts->getValues(), ($page - 1) * $count, $count);

        /** @var BasketProduct $product */
        foreach ($basketProducts as $product){
            if($count <= 0 || count($parsedProducts) === $count){
                break;
            }

            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages
        ]);
    }

    private function getCartForGuest(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cartCookies = $request->cookies->get("guest-cart");

        $cart = new Basket(new Client("", "", "", "", null, null));

        if(!$cartCookies){
            return $cart;
        }

        $cartCookies = json_decode($cartCookies, true);

        $basketProducts = new ArrayCollection();

        foreach ($cartCookies["products"] as $id => $count){
            $product = $em->getRepository(Product::class)->find($id);

            if(!$product){
                continue;
            }

            $cartProduct = new BasketProduct($product, $cart, $count);

            $basketProducts->add($cartProduct);
        }

        $cart->setBasketProducts($basketProducts);

        if($cartCookies["discount"] > 0){
            $promocode = new PromoCode();
            $promocode->setDiscount($cartCookies["discount"]);

            $cart->setPromoCode($promocode);
        }

        return $cart;
    }
}