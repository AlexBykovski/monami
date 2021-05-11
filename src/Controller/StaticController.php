<?php

namespace App\Controller;

use App\Entity\AboutPage;
use App\Entity\ConditionsPage;
use App\Entity\ContactsPage;
use App\Entity\DiscountedItemPage;
use App\Entity\DiscountPage;
use App\Entity\Manager;
use App\Entity\ManagerSlider;
use App\Entity\VacanciesPage;
use App\Entity\Vacancy;
use App\Entity\VacancyBlock;
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
        return $this->render('client/static/about.html.twig', [
            "page" => $this->getDoctrine()->getManager()->getRepository(AboutPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/conditions", name="show_static_conditions")
     */
    public function showConditionsAction(Request $request)
    {
        return $this->render('client/static/conditions.html.twig', [
            "page" => $this->getDoctrine()->getManager()->getRepository(ConditionsPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/contacts", name="show_static_contacts")
     */
    public function showContactsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('client/static/contacts.html.twig', [
            "managers" => $em->getRepository(ManagerSlider::class)->findAll(),
            "page" => $em->getRepository(ContactsPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/discounted", name="show_static_discounted")
     */
    public function showDiscountedAction(Request $request)
    {
        return $this->render('client/static/discounted_goods.html.twig', [
            "page" => $this->getDoctrine()->getManager()->getRepository(DiscountedItemPage::class)->findAll()[0]
        ]);
    }

    /**
     * @Route("/vacancies", name="show_static_vacancies")
     */
    public function showVacanciesAction(Request $request)
    {
        $result = [];

        $vacancy = $this->getDoctrine()->getManager()->getRepository(Vacancy::class)->findAll();

        /** @var Vacancy $item */
        foreach ($vacancy as $item) {
            $result[$item->getTitle()] = $item->getVacancyBlocks();
        }


        return $this->render('client/static/vacancies.html.twig', [
            "vacancies" => $result
        ]);
    }
}
