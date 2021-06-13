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
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/cart")
 *
 */
class CartController extends Controller
{
    /**
     * @Route("/add-product-to-cart", name="add_product_to_cart")
     */
    public function addProductToCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = (int)$content["count"];

        if (!$count || !$idProduct) {
            return new JsonResponse([
                "cart" => $basket->toArray(),
                "text" => "Ошибка при добавлении товара",
                "type" => "warn",
            ]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if (!($product instanceof Product)) {
            return new JsonResponse([
                "cart" => $basket->toArray(),
                "text" => "Ошибка при добавлении товара",
                "type" => "warn",
				'id' => $idProduct,
            ]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if ($basketProduct instanceof BasketProduct) {
            $basketProduct->setCount($basketProduct->getCount() + $count);
        } else {
            $newBasketProduct = new BasketProduct($product, $basket, $count);

            if ($user) {
                $em->persist($newBasketProduct);
            }

            $basketProducts = $basket->getBasketProducts();
            $basketProducts->add($newBasketProduct);
            $basket->setBasketProducts($basketProducts);
        }

        $product->removeCount($count);

        $em->flush();

        if ($user) {
            $em->refresh($basket);
        }


        return new JsonResponse([
            "cart" => $basket->toArray(),
            "text" => "Добавлено в корзину: " . $product->getName() . " " . $count . " ед.",
            "type" => "info",
        ]);
    }

    /**
     * @Route("/remove-product-from-cart", name="remove_product_from_cart")
     */
    public function removeProductFromCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = $content['count'];

        if (!$idProduct) {
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if (!($product instanceof Product)) {
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if ($basketProduct instanceof BasketProduct) {
            $product->addCount($basketProduct->getCount());

            if ($user) {

            	if(!$count) {
					$em->remove($basketProduct);
				} else {
            		$basketProduct->setCount($count);
            		$em->persist($basketProduct);
				}

            	$em->flush();
            } else {
                $basketProducts = new ArrayCollection();

                /** @var BasketProduct $basketProductCookie */
                foreach ($basket->getBasketProducts() as $basketProductCookie) {
                    if ($basketProductCookie->getProduct()->getId() !== $idProduct) {
                        $basketProducts->add($basketProductCookie);
                    } else {
                    	if($count){
                    		$basketProductCookie->setCount($count);
							$basketProducts->add($basketProductCookie);
						}
					}
                }

                $basket->setBasketProducts($basketProducts);
            }
        }

        if ($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/save-cart", name="save_cart")
     */
    public function saveCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        /** @var Client $user */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $lastPurchase = $em->getRepository(Purchase::class)->findBy([], ['id' => 'DESC'], 1);

        $date = date('Y-m-d_H-i-s');

        $xml = '﻿<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<order date ="' . $date . '">';

        if ($lastPurchase) {
            $orderId = $lastPurchase[0]->getOrderid() + 1;
        } else {
            $orderId = 1;
        }

        $emailMessage = '<b>Заказ N W' . str_pad($orderId, 5, '0', STR_PAD_LEFT) . '</b><br>';

        if ($user) {
            $xml .= '<customer id="' . $user->getApiId() . '" managerId="' . $user->getManager()->getApiId() . '">
                    <name value="' . $user->getUsername() . '"/>
                    <email value="' . $user->getEmail() . '"/>
                    <phone value="' . $user->getPhone() . '"/>
                    <address value=""/>
                    <info value=""/>
                 </customer>
                 <items>';

            $emailFinalMessage = '
            <p>
            <b>Имя/ФИО</b>: ' . $user->getUsername() . '<br>
            <b>Email:</b> <a href="mailto:' . $user->getEmail() . '" target="_blank">' . $user->getEmail() . '</a><br>
            <b>Телефон</b>: ' . $user->getPhone() . '<br>
            <b>Адрес</b>: <br>
            <b>Пожелания</b>: <u></u><u></u></p>';
        } else {
            $content = json_decode($request->getContent(), true);

            if (key_exists('data', $content)) {
                $content = $content['data'];
            }

            if (!array_key_exists('info', $content)) {
                $content['info'] = '';
            }

            $xml .= '<customer id="" managerId="">
                    <name value="' . $content['username'] . '"/>
                    <email value="' . $content['email'] . '"/>
                    <phone value="' . $content['phone'] . '"/>
                    <address value="' . $content['address'] . '"/>
                    <info value="' . $content['info'] . '"/>
                 </customer>
                 <items>';
            $emailFinalMessage = '
            <p>
            <b>Имя/ФИО</b>: ' . $content['username'] . '<br>
            <b>Email:</b> <a href="mailto:' . $content['email'] . '" target="_blank">' . $content['email'] . '</a><br>
            <b>Телефон</b>: ' . $content['phone'] . '<br>
            <b>Адрес</b>:  ' . $content['address'] . '<br>
            <b>Пожелания</b>: ' . $content['info'] . '<u></u><u></u></p>';

        }

        $emailMessage .= '<table border="1" cellspacing="0" cellpadding="0">
                 <tbody>
                     <tr>
                         <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Код</b><u></u><u></u></p></td>
                         <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Товары</b><u></u><u></u></p></td>
                         <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Кол-во</b><u></u><u></u></p></td>
                         <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Цена (Руб.)</b><u></u><u></u></p></td>
                         <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Сумма (Руб.)</b><u></u><u></u></p></td>
                     </tr>';

        $fullPrice = 0;

        /** @var BasketProduct $basketProductCookie */
        foreach ($basket->getBasketProducts() as $basketProductCookie) {
            $basketProduct = $basketProductCookie->getProduct();

			$xml .= '<item id="' . $basketProduct->getApiId()
				. '" price="' . $basketProduct->getCost()
				. '" quantity="' . $basketProductCookie->getCount() . '"/>';

            $purchase = new Purchase(
                $basketProduct,
                $basketProductCookie->getCount(),
                $user ? $user : null
            );
            $emailMessage .= '<tr>
            <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal">' . $basketProduct->getApiId() . '<u></u><u></u></p></td>
            <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal">' . $basketProduct->getName() . '<u></u><u></u></p></td>
            <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal">' . $basketProductCookie->getCount() . '<u></u><u></u></p></td>
            <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal">' . $basketProductCookie->getCostByUser()  . '<u></u><u></u></p></td>
            <td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal">' . $basketProductCookie->getCount() * $basketProductCookie->getCostByUser() . '<u></u><u></u></p></td>
                </tr>';

            $fullPrice += ($basketProductCookie->getCount() * $basketProductCookie->getCostByUser());

            $purchase->setOrderId(str_pad($orderId, 5, '0', STR_PAD_LEFT));

            $em->persist($purchase);

            $em->remove($basketProductCookie);
            $em->flush();
        }

        $emailMessage .= '
<tr><td colspan="4" style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>Итого</b><u></u><u></u></p></td>
<td style="padding:3.0pt 3.0pt 3.0pt 3.0pt"><p class="MsoNormal"><b>' . $fullPrice . '</b><u></u><u></u></p></td></tr>
</tbody>
</table>';

        $xml .= '</items>
</order>';

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);

        $dom->save('../monami/public/xml/' . urlencode($date) . '-' . str_pad($orderId, 5, '0', STR_PAD_LEFT) . '.xml');

        $to = 'info@monami.by';
//        $to = 'kirillooo888@gmail.com';
        $subject = 'Новый заказ № W' . str_pad($orderId, 5, '0', STR_PAD_LEFT);
        $message = $emailMessage . $emailFinalMessage;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: site@monami.by' . "\r\n" .
            'Reply-To: site@monami.by' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        setcookie('guest-cart', '', time() - 3600, '/');

		foreach ($_COOKIE as $key => $cookie){
			if($cookie == 'free'){
				setcookie($key,'busy',time() * 3600*24*30, '/');
			}
		}

        if ($user) {
        	$basket->setDiscount(null);
        	$em->persist($basket);
        	$em->flush();
            $em->refresh($basket);
        }

        return new JsonResponse([
        "cart" => [],
        "text" => "Добавлено в корзину: 1",
        "type" => "info",
    ]);
    }

    /**
     * @Route("/export-to-xls", name="export_to_xls")
     */
    public function exportToXlsAction(Request $request)
    {
        /** @var Client $user */
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);

        $spreadsheet = new Spreadsheet();

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $lastPurchase = $em->getRepository(Purchase::class)->findBy([], ['id' => 'DESC'], 1);

        if ($lastPurchase) {
            $orderId = $lastPurchase[0]->getOrderid() + 1;
        } else {
            $orderId = 1;
        }

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Заказ № W' . str_pad($orderId, 5, '0', STR_PAD_LEFT));

        $startNum = 3;

        $sheet->setCellValue('A' . $startNum, 'Код');
        $sheet->setCellValue('B' . $startNum, 'Товары');
        $sheet->setCellValue('C' . $startNum, 'Кол-во');
        $sheet->setCellValue('D' . $startNum, 'Цена(Руб.)');
        $sheet->setCellValue('E' . $startNum, 'Сумма(Руб.)');

        $startNum++;

        $sheet->setTitle("Order_" . date('Y-m-d'));

        $fullPrice = 0;

        function getPrice($price)
        {
            return str_replace('.', ',', number_format($price, 2));
        }

        /** @var BasketProduct $basketProductCookie */
		foreach ($basket->getBasketProducts() as $basketProductCookie) {
            /** @var Product $basketProduct */
            $basketProduct = $basketProductCookie->getProduct();
            $cost = $basketProductCookie->getCostByUser();
            $count = $basketProductCookie->getCount();
            $price = $cost * $count;

            $fullPrice += $price;

            $sheet->setCellValue('A' . $startNum, $basketProduct->getApiId());
            $sheet->setCellValue('B' . $startNum, $basketProduct->getName());
            $sheet->setCellValue('C' . $startNum, $count);
            $sheet->setCellValue('D' . $startNum, getPrice($cost));
            $sheet->setCellValue('E' . $startNum, getPrice($price));

            $startNum++;
        }
        $dataNum = $startNum - 1;

        $sheet->setCellValue('A' . $startNum, 'Итого');
        $sheet->setCellValue('E' . $startNum, getPrice($fullPrice));

        if ($user) {
            $startNum++;
            $startNum++;

            $sheet->setCellValue('A' . $startNum, 'Имя/ФИО:');
            $sheet->setCellValue('B' . $startNum, $user->getUsername());
            $startNum++;

            $sheet->setCellValue('A' . $startNum, 'Email:');
            $sheet->setCellValue('B' . $startNum, $user->getEmail());
            $startNum++;

            $sheet->setCellValue('A' . $startNum, 'Телефон:');
            $sheet->setCellValue('B' . $startNum, $user->getPhone());
            $startNum++;

            $sheet->setCellValue('A' . $startNum, 'Адрес:');
            $sheet->setCellValue('B' . $startNum, '');
            $startNum++;

            $sheet->setCellValue('A' . $startNum, 'Пожелания:');
            $sheet->setCellValue('B' . $startNum, '');

        }

        foreach (range('A', 'E') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->calculateColumnWidths();

        $spreadsheet->getActiveSheet()->getStyle("A4:E4")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => 'dashed',
                    )
                )
            )
        );

        $writer = new Xlsx($spreadsheet);

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath = $publicDirectory . '/' . "Order_" . date('Y-m-d') . '.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        $response = new BinaryFileResponse($excelFilepath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    /**
     * @Route("/change-count-product-in-cart", name="change_count_product_in_cart")
     */
    public function changeCountProductInCartAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $idProduct = $content["idProduct"];
        $count = (int)$content["count"];

        if (!$idProduct || !$count) {
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $product = $em->getRepository(Product::class)->find($idProduct);

        if (!($product instanceof Product)) {
            return new JsonResponse(["cart" => $basket->toArray()]);
        }

        $basketProduct = $basket->getBasketProductById($idProduct);

        if ($basketProduct instanceof BasketProduct) {
            $absDiff = abs($basketProduct->getCount() - $count);

            if ($basketProduct->getCount() > $count) {
                $product->removeCount($absDiff);
            } else {
                $product->addCount($absDiff);
            }

            $basketProduct->setCount($count);

            if ($basketProduct->getCount() === 0 && $user) {
                $em->remove($basketProduct);
            }

            $basketProducts = new ArrayCollection();

            /** @var BasketProduct $basketProductCookie */
            foreach ($basket->getBasketProducts() as $basketProductCookie) {
                if ($basketProductCookie->getProduct()->getId() === $idProduct) {
                    $basketProducts->add($basketProductCookie);
                }
            }

            $basketProducts->add($basketProduct);

            $basket->setBasketProducts($basketProducts);

            $em->flush();
        }

        if ($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray()]);
    }

    /**
     * @Route("/use-promocode", name="use_promocode")
     */
    public function usePromoCodeAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $content = json_decode($request->getContent(), true);
        $code = $content["code"];

        if (!($code)) {
            return new JsonResponse(["cart" => $basket->toArray(), 'message' => 'Промокод не распостраняется']);
        }

        $promoCode = $em->getRepository(PromoCode::class)->findOneBy(["code" => $code]);

        if (!($promoCode instanceof PromoCode)) {
            return new JsonResponse(["cart" => $basket->toArray(), 'message' => 'Промокод не распостраняется']);
        }

		if (isset($_COOKIE[$code])) {
			return new JsonResponse(["cart" => $basket->toArray(), 'message' => 'Ваш промокод уже применен']);
		} else {
			setcookie($code, 'free', time() + 3600 * 24 * 30, '/');
		}

        $basket->setPromoCode($promoCode);
        $promoCode->setIsUsed(true);
        $promoCode->incUsages();

        $em->flush();

        if ($user) {
            $em->refresh($basket);
        }

        return new JsonResponse(["cart" => $basket->toArray(), 'message' => 'Спасибо, ваш промокод применён']);
    }

    /**
     * @Route("/filter", name="filter_cart_products")
     */
    public function filterSubcategoriesAction(Request $request)
    {
        $user = $this->getUser();
        /** @var Basket $basket */
        $basket = $user ? $user->getBasket() : $this->getCartForGuest($request);

        $params = $request->query->all();

        $count = (int)$params["count"];
        $sort = $params["sort"];
        $page = array_key_exists("page", $params) ? (int)$params["page"] : 1;
        $allBasketProducts = $basket->getBasketProducts($sort);
        $fullCount = $allBasketProducts->count();
        $countPages = (int)($fullCount % $count === 0 ? $fullCount / $count : $fullCount / $count + 1);
        $parsedProducts = [];

        $basketProducts = array_slice($allBasketProducts->getValues(), ($page - 1) * $count, $count);

        /** @var BasketProduct $product */
        foreach ($basketProducts as $product) {
            if ($count <= 0 || count($parsedProducts) === $count) {
                break;
            }

            $parsedProducts[] = $product->toArray();
        }

        return new JsonResponse([
            "products" => $parsedProducts,
            "countPages" => $countPages
        ]);
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
					$cart->setPromoCode($promocode);
				}
			}
		}

		if (!$cartCookies) {
            return $cart;
        }

        $cartCookies = json_decode($cartCookies, true);

        $basketProducts = new ArrayCollection();

        foreach ($cartCookies["products"] as $id => $count) {
            $product = $em->getRepository(Product::class)->find($id);

            if (!$product) {
                continue;
            }

            $cartProduct = new BasketProduct($product, $cart, $count);

            $basketProducts->add($cartProduct);
        }

        $cart->setBasketProducts($basketProducts);

        if ($cartCookies["discount"] > 0) {
            $promocode = new PromoCode();
            $promocode->setDiscount($cartCookies["discount"]);

            $cart->setPromoCode($promocode);
        }

        return $cart;
    }
}