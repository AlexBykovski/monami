<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Feedback;
use App\Entity\Manager;
use App\Entity\Product;
use App\Entity\ProductGroup;
use App\Form\Type\FeedbackForm;
use App\Helper\UserSupportHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @Route("/help")
 */
class HelpController extends Controller
{
	/**
	 * @Route("/", name="show_help")
	 */
	public function showHelpAction(Request $request, UserSupportHelper $helper)
	{
		$feedback = new Feedback();
		$em = $this->getDoctrine()->getManager();

		$form = $this->createForm(FeedbackForm::class, $feedback);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$feedback->setUser($this->getUser());

			$em->persist($feedback);
			$em->flush();

			$helper->sendEmail($feedback);

			$form = $this->createForm(FeedbackForm::class, new Feedback());

			return $this->render('client/help/help.html.twig', [
				'form' => $form->createView(),
				'gotMessage' => true
			]);
		}


		return $this->render('client/help/help.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/export", name="show_xml")
	 */

	public function exportXML()
	{
		$products = $this->getDoctrine()
			->getRepository(Product::class)
			->findAll();

		$categories = $this->getDoctrine()
			->getRepository(ProductGroup::class)
			->findAll();


		header("Content-type: text/xml");
		$text = '<?xml version="1.0" encoding="UTF-8"?>
        <yml_catalog date="' . date('Y-m-d H:i:s') . '">
            <shop>
                <name>Монами</name>
                <company>Монами</company>
                <url>https://монами.бел</url>
                <currencies>
                    <currency id="BYN" rate="1"/>
                </currencies>
                <categories>';

		$categoriesParents = [];
		$sales = [];

		/** @var ProductGroup $category */
		foreach ($categories as $category) {
//			if ($category->getSale() == 0 && !is_int(stripos($category->getName(), 'РАСПРОДАЖА'))
//				&& !is_int(stripos($category->getName(), 'АКЦИЯ'))
//
//			) {
				if ($category->getParentGroup() == null) {
					$text .= '<category 
            id = "' . $category->getId() . '" 
            url = "http://монами.бел/catalog/' . $category->getId() . '" > 
            ' . trim($category->getName()) . '
            </category >';
				} else {
					$text .= '<category 
            id = "' . $category->getId() . '" 
            parentId = "' . $category->getParentGroup()->getId() . '"
            url = "http://монами.бел/catalog/' . $category->getId() . '" 
            > 
            ' . trim($category->getName()) . '
            </category >';
				}
//			} else {
//				$sales[$category->getId()] = $category->getId();
//			}
		}

		$text .= '</categories>
                <offers>';

		/** @var Product $product */
		foreach ($products as $product) {
			if ($product->getLeftCount() > 0 && $product->getProductGroup() !== null ) {
				$text .= '<offer id="' . $product->getId() . '" available="true" leftovers="' . $product->getLeftCount() . '">
                        <url>http://монами.бел/product/' . $product->getId() . '</url>
                        <price>' . $product->getRozCost() . '</price>
                        <picture>' . $product->getPhoto() . '</picture>
                        <name>' . $product->getName() . '</name>
                        <description>' . $product->getDescription() . '</description>
                        <currencyId>BYN</currencyId>
                        <quantity>' . $product->getLeftCount() . '</quantity>
                        <categoryId>' . $product->getProductGroup()->getId() . '</categoryId>
                    </offer>';
			}
		}


		$text .= '</offers>
            </shop>
        </yml_catalog>';

		echo $text;
		die;
	}

	/**
	 * @Route("/export_managers", name="show_xml_managers")
	 */
	public function exportManagers()
	{
		$managers = $this->getDoctrine()
			->getRepository(Manager::class)
			->findAll();


		header("Content-type: text/xml");
		$text = '<?xml version="1.0" encoding="windows-1251"?><Менеджеры>';

		/** @var Product $product */
		foreach ($managers as $manager) {
			$text .= '<Элемент Код="' . $manager->getApiId() . '" Менеджер="' . $manager->getFullName() . '" email="' . $manager->getEmail() . '" Телефон="' . $manager->getPhone() . '" Фото="' .
				str_replace('http://213.184.224.60:58080/Sources/Forsite/FotoManager/', '',
					$manager->getImage()) . '"/>';
		}


		$text .= '</Менеджеры>';

		echo $text;
		die;
	}

	/**
	 * @Route("/export_clients", name="show_xml_clients")
	 */
	public function exportClients()
	{
		$managers = $this->getDoctrine()
			->getRepository(Client::class)
			->findAll();


		header("Content-type: text/xml");
		$text = '<?xml version="1.0" encoding="windows-1251"?><Клиенты>';

		/** @var Product $product */
		foreach ($managers as $client) {
			$text .= '<Элемент 
			Код="' . $client->getApiId() . '"
			Контрагент="' . $client->getManager()->getFullName() . '"
			Логин="' . $client->getUsername() . '"
			Пароль="' . $client->getPassword() . '"
			email="' . $client->getEmail() . '"
			телефоны="' . $client->getPhone() . '"
			Менеджер="' . $client->getManager()->getFullName() . '"
			КодМенеджера="' . $client->getManager()->getApiId() . '"
       		/>';
		}


		$text .= '</Клиенты>';

		echo $text;
		die;
	}

	/**
	 * @Route("/update_clients", name="update_clients")
	 */
	public function updateClients(KernelInterface $kernel)
	{
		set_time_limit(600);

		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput(array(
			'command' => 'app:import:clients',
			// (необязательно) определить значение аргументов команды
		));

		// Вы можете использовать NullOutput(), если вам не нужен вывод
		$output = new BufferedOutput();
		$application->run($input, $output);

		// вернут вывод, не используйте, если вы использовали NullOutput()
		$content = $output->fetch();

		// вернуть новый Response(""), если вы использовали NullOutput()
		return new Response('success');
	}

	/**
	 * @Route("/update_products", name="update_products")
	 */
	public function updateProducts(KernelInterface $kernel)
	{
		set_time_limit(600);

		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput(array(
			'command' => 'app:import:products',
			// (необязательно) определить значение аргументов команды
		));

		// Вы можете использовать NullOutput(), если вам не нужен вывод
		$output = new BufferedOutput();
		$application->run($input, $output);

		// вернут вывод, не используйте, если вы использовали NullOutput()
		$content = $output->fetch();

		// вернуть новый Response(""), если вы использовали NullOutput()
		return new Response('success');
	}

	/**
	 * @Route("/update_managers", name="update_managers")
	 */
	public function updateManagers(KernelInterface $kernel)
	{
		set_time_limit(600);

		$application = new Application($kernel);
		$application->setAutoExit(false);

		$input = new ArrayInput(array(
			'command' => 'app:import:managers',
			// (необязательно) определить значение аргументов команды
		));

		// Вы можете использовать NullOutput(), если вам не нужен вывод
		$output = new BufferedOutput();
		$application->run($input, $output);

		// вернут вывод, не используйте, если вы использовали NullOutput()
		$content = $output->fetch();

		// вернуть новый Response(""), если вы использовали NullOutput()
		return new Response('success');
	}
}
