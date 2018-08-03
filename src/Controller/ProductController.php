<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="show_product")
     */
    public function showProductAction(Request $request)
    {
        return $this->render('client/product/product.html.twig', []);
    }
}