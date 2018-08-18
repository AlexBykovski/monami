<?php

namespace App\Controller;

use App\Entity\MainPage;
use App\Entity\Product;
use App\Entity\ProductGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // @@todo its example, need correct algorithm for hits
        $hits = $em->getRepository(Product::class)->findBy([], null, 4, 50);
        $baseGroups = $em->getRepository(ProductGroup::class)->findBy(["parentGroup" => null]);

        /** @var MainPage $mainPage */
        $mainPage = $em->getRepository(MainPage::class)->findAll()[0];

        return $this->render('client/default/homepage.html.twig', [
            "hits" => $hits,
            "baseGroups" => $baseGroups,
            "slides" => $mainPage->getLinkImages()
        ]);
    }
}