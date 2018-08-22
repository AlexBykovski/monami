<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/profile")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/basket", name="show_profile_basket")
     */
    public function showProfileBasketAction(Request $request)
    {
        return $this->render('client/profile/profile_basket.html.twig', []);
    }

    /**
     * @Route("/feedback", name="show_profile_feedback")
     */
    public function showProfileFeedbackAction(Request $request)
    {
        return $this->render('client/profile/profile_feedback.html.twig', []);
    }

    /**
     * @Route("/orders", name="show_profile_orders")
     */
    public function showProfileOrdersAction(Request $request)
    {
        return $this->render('client/profile/profile_orders_history.html.twig', []);
    }

    /**
     * @Route("/personal", name="show_profile_personal")
     */
    public function showProfilePersonalAction(Request $request)
    {
        return $this->render('client/profile/profile_personal.html.twig', []);
    }
}