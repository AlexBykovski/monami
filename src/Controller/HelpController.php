<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/help")
 */
class HelpController extends Controller
{
    /**
     * @Route("/", name="show_help")
     */
    public function showHelpAction(Request $request)
    {
        return $this->render('client/help/help.html.twig', []);
    }
}