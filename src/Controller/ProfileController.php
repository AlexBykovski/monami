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
            "cart" => $this->getCartForGuest($request),
            'user' => $this->getUser(),
			'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,

        ]);
    }

    /**
     * @Route("/feedback", name="show_profile_feedback")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProfileFeedbackAction(Request $request)
    {
        return $this->render('client/profile/profile_feedback.html.twig', ['user' => $this->getUser()]);
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

        $orders = [];
		foreach ($purchases as $purchase){
			$orders[$purchase->getOrderId()] = [
				'purchases' => [],
				'items' => [],
			];
		}

		krsort($orders);

        foreach ($purchases as $purchase){
        	$orders[$purchase->getOrderId()]['purchases'][] = $purchase;
			$orders[$purchase->getOrderId()]['items'][] = [
				'productId' =>	$purchase->getProduct()->getId(),
				'count' => $purchase->getCount(),
			];
			$orders[$purchase->getOrderId()]['id'] = '№ W' . str_pad($purchase->getOrderId(), 5, '0', STR_PAD_LEFT);
			$orders[$purchase->getOrderId()]['date'] = $purchase->getCreatedAt();
		}

        return $this->render('client/profile/profile_orders_history.html.twig', [
            'orders' => $orders,
            'user' => $this->getUser(),
			'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
        ]);
    }

    /**
     * @Route("/personal", name="show_profile_personal")
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function showProfilePersonalAction(Request $request)
    {
        $user = $this->getUser();

        return $this->render(
            'client/profile/profile_personal.html.twig',
            [
                'user' => $user,
				'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
            ]
        );
    }

    private function getCartForGuest(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cartCookies = $request->cookies->get("guest-cart");

        $cart = new Basket(new Client("", "", "", "", null, null));

		foreach ($_COOKIE as $key => $cookie){
			if($cookie == 'free'){
				/** @var PromoCode $promocode */
				$promocode = $em->getRepository(PromoCode::class)->findOneBy(["code" => $key]);
				if($promocode) {
					$cart->setDiscount($promocode->getDiscount());
				}
			}
		}

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