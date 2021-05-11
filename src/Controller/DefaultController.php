<?php

namespace App\Controller;

use App\Entity\MainPage;
use App\Entity\Product;
use App\Entity\ProductGroup;
use http\Client\Curl\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function showHomePageAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		// @@todo its example, need correct algorithm for hits
		$hits = $em->getRepository(Product::class)->findBy([], ['id' => 'DESC'], 40, 0);
		$baseGroups = $em->getRepository(ProductGroup::class)->findBy(["parentGroup" => null]);

		/** @var MainPage $mainPage */
		$mainPage = $em->getRepository(MainPage::class)->findAll()[0];

		foreach ($hits as &$hit) {
			$hit = [
				"photo" => $hit->getPhoto(),
				"name" => $hit->getName(),
				"cost" => $hit->getCost(),
				'oldcost' => $hit->getOldcost(),
				'rozcost' => $hit->getRozCost(),
				"apiId" => $hit->getApiId(),
				"id" => $hit->getId(),
				"description" => $hit->getDescription(),
				"leftCount" => $hit->getLeftCount(),
				"createdAt" => $hit->getCreatedAt() ? $hit->getCreatedAt()->format("Y-m-d H:i:s") : "",
			];
		}

		return $this->render('client/default/homepage.html.twig', [
			"hits" => $hits,
			"baseGroups" => $baseGroups,
			"slides" => $mainPage->getLinkImages(),
			'user' => $this->getUser(),
			'userSale' => $this->getUser() ? $this->getUser()->getDiscount() : 0,
			]);
	}

	/**
	 * @Route("/check_login_new", name="check_login_new")
	 */
	public function check_login_new(Request $request, UserPasswordEncoderInterface $encoder)
	{
		$lastUsername = $request->request->get('_username');
		$password = $request->request->get('_password');
		$error = '';

		$username = $lastUsername;
		$user = $this->container
			->get('doctrine')
			->getRepository(\App\Entity\User::class)
			->findByUsername($username);

		if (!$user) {
			$error = 'Пользователя с такими данными не существует';
		} else {
			$user = $user[0];

			$plainPassword = $password;
			$encoder = $this->container->get('security.password_encoder');

			if (!$encoder->isPasswordValid($user, $plainPassword)) {
				$error = 'Данные введены не верно';
			}
		}

		return new Response(json_encode(array(
			'last_username' => $lastUsername,
			'error' => $error,
		)));
	}
}