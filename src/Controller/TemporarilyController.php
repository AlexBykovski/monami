<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TemporarilyController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('temporarily/about.html.twig', []);
    }

    /**
     * @Route("/account-basket", name="account_basket")
     */
    public function accountBasketAction(Request $request)
    {
        return $this->render('temporarily/account_basket.html.twig', []);
    }

    /**
     * @Route("/account-feedback", name="account_feedback")
     */
    public function accountFeedbackAction(Request $request)
    {
        return $this->render('temporarily/account_feedback.html.twig', []);
    }

    /**
     * @Route("/account-orders", name="account_orders")
     */
    public function accountOrdersAction(Request $request)
    {
        return $this->render('temporarily/account_orders_history.html.twig', []);
    }

    /**
     * @Route("/account-personal", name="account_personal")
     */
    public function accountPersonalAction(Request $request)
    {
        return $this->render('temporarily/account_personal.html.twig', []);
    }

    /**
     * @Route("/catalog", name="catalog")
     */
    public function catalogAction(Request $request)
    {
        return $this->render('temporarily/catalog.html.twig', []);
    }

    /**
     * @Route("/conditions", name="conditions")
     */
    public function conditionsAction(Request $request)
    {
        return $this->render('temporarily/conditions.html.twig', []);
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction(Request $request)
    {
        return $this->render('temporarily/contacts.html.twig', []);
    }

    /**
     * @Route("/discounted", name="discounted")
     */
    public function discountedAction(Request $request)
    {
        return $this->render('temporarily/discounted_goods.html.twig', []);
    }

    /**
     * @Route("/help", name="help")
     */
    public function helpAction(Request $request)
    {
        return $this->render('temporarily/help.html.twig', []);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homePageAction(Request $request)
    {
        return $this->render('temporarily/homepage.html.twig', []);
    }

    /**
     * @Route("/product", name="product")
     */
    public function productAction(Request $request)
    {
        return $this->render('temporarily/product.html.twig', []);
    }

    /**
     * @Route("/subcategories", name="subcategories")
     */
    public function subcategoriesAction(Request $request)
    {
        return $this->render('temporarily/subcategories.html.twig', []);
    }

    /**
     * @Route("/vacancies", name="vacancies")
     */
    public function vacanciesAction(Request $request)
    {
        return $this->render('temporarily/vacancies.html.twig', []);
    }
}