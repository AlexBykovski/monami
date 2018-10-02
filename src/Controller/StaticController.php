<?php

namespace App\Controller;

use App\Entity\ContactsPage;
use App\Entity\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends Controller
{
    /**
     * @Route("/about", name="show_static_about")
     */
    public function showAboutAction(Request $request)
    {
        return $this->render('client/static/about.html.twig', []);
    }

    /**
     * @Route("/conditions", name="show_static_conditions")
     */
    public function showConditionsAction(Request $request)
    {
        return $this->render('client/static/conditions.html.twig', []);
    }

    /**
     * @Route("/contacts", name="show_static_contacts")
     */
    public function showContactsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('client/static/contacts.html.twig', [
            "managers" => $em->getRepository(Manager::class)->findAll(),
            "page" => $em->getRepository(ContactsPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/discounted", name="show_static_discounted")
     */
    public function showDiscountedAction(Request $request)
    {
        return $this->render('client/static/discounted_goods.html.twig', []);
    }

    /**
     * @Route("/vacancies", name="show_static_vacancies")
     */
    public function showVacanciesAction(Request $request)
    {
        return $this->render('client/static/vacancies.html.twig', []);
    }
}