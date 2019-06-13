<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Client;
use App\Entity\Product;
use App\Entity\PromoCode;
use App\Entity\Purchase;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/profile")
 *
 */
class ProfileController extends Controller
{
    /**
     * @Route("/basket", name="show_profile_basket")
     */
    public function showProfileBasketAction(Request $request)
    {
        return $this->render('client/profile/profile_basket.html.twig', [
            "cart" => $this->getCartForGuest($request)
        ]);
    }

    /**
     * @Route("/feedback", name="show_profile_feedback")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProfileFeedbackAction(Request $request)
    {
        return $this->render('client/profile/profile_feedback.html.twig', []);
    }

    /**
     * @Route("/orders", name="show_profile_orders")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProfileOrdersAction(Request $request)
    {
        /** @var Client $user */
        $user = $this->getUser();
        $purchases = $user->getPurchases();

        return $this->render('client/profile/profile_orders_history.html.twig', [
            'purchases' => $purchases,
        ]);
    }

    /**
     * @Route("/personal", name="show_profile_personal")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProfilePersonalAction(Request $request)
    {
        return $this->render('client/profile/profile_personal.html.twig', []);
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