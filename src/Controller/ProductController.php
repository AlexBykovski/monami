<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends Controller
{
    /**
     * @Route("/product/{idProduct}", name="show_product")
     *
     * @ParamConverter("product", class="App:Product", options={"id" = "idProduct"})
     */
    public function showProductAction(Request $request, Product $product)
    {

        return $this->render('client/product/product.html.twig', [
            "product" => $product,
            'user' => $this->getUser(),
            'previousUrl' => $request->headers->get('referer'),
        ]);
    }
}