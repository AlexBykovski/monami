<?php

namespace App\Command;

use App\Entity\ImportDetail;
use App\Entity\Product;
use App\Entity\ProductGroup;
use App\Import\XMLDataImporter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProductsCommand extends ContainerAwareCommand
{
	const ATTRIBUTES = "@attributes";
	const ELEMENT = "Элемент";
	const CODE = "Код";
	const SIMA_CODE = "СимаКод";
	const NAME = "Наименование";
	const PHOTO = "Фото";
	const IS_GROUP = "ЭтоГруппа";
	const GROUP_DISCOUNT = 'СкидкаНаГруппу';
	const GROUP = "Группа";
	const COST = "Цена";
	const LEFT_COUNT = "Остаток";
	const ROZ_COST = "ЦенаО";
	const DESCRIPTION = "Описание";
	const TEXT_DESCRIPTION = "ТекстОписания";

	private $currentProducts = [];
	private $currentGroups = [];

	protected function configure()
	{
		$this
			->setName('app:import:products')
			->setDescription('Import products');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var XMLDataImporter $importer */
		$importer = $this->getContainer()->get("app.import.import_xml_data");
		/** @var EntityManagerInterface $em */
		$em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

		/** @var ImportDetail $importDetail */
		$importDetail = $em->getRepository(ImportDetail::class)->findOneBy(["nameCode" => "products"]);
		$importDetail->setLastUpdateStatus(ImportDetail::STATUS_SUCCESS);
		$importDetail->setLastUpdatedAt(new DateTime());

		$data = $importer->importData($importDetail);

		if (!is_array($data) || !array_key_exists(self::ELEMENT, $data) || !count($data[self::ELEMENT])) {
//			$output->writeln("<comment>Empty import file!</comment>");
			return false;
		}

		$groups = [];
		$products = [];

		//import groups and products
		foreach ($data[self::ELEMENT] as $index => $datum) {
			$element = $datum[self::ATTRIBUTES];

			if (!is_array($element)) {

				continue;
			}

			$group = array_key_exists(self::GROUP, $element) && array_key_exists($element[self::GROUP], $groups) ?
				$groups[$element[self::GROUP]] : null;

			if (array_key_exists(self::IS_GROUP, $element) && $element[self::IS_GROUP] == '1') {
				$groups[$element[self::CODE]] = $this->createProductGroup($em, $element, $group,
					$importDetail->getImagesUrl());
			} else {
				if ((float)str_replace(',', '.', $element[self::COST]) > 0) {
					$products[$element[self::CODE]] = $this->createProduct($em, $element, $group,
						$importDetail->getImagesUrl());
				}
			}
		}

		//import absent groups
		foreach ($data[self::ELEMENT] as $datum) {
			$element = $datum[self::ATTRIBUTES];

			$group = array_key_exists(self::GROUP, $element) && array_key_exists($element[self::GROUP], $groups) ?
				$groups[$element[self::GROUP]] : null;

			if (array_key_exists(self::GROUP, $element) && !$group) {
//				$output->writeln("<error>No exist group: " . $element[self::GROUP] . "</error>");

				continue;
			} elseif (!array_key_exists(self::GROUP, $element)) {
				continue;
			}

			if (array_key_exists(self::IS_GROUP, $element) && $element[self::IS_GROUP] == '1') {
				$groups[$element[self::CODE]]->setParentGroup($group, $importDetail->getImagesUrl());
			} else {
				$products[$element[self::CODE]]->setProductGroup($group, $importDetail->getImagesUrl());
			}
		}

		$em->flush();

		$em->getRepository(Product::class)->updateNotIds($this->currentProducts);
		$em->getRepository(ProductGroup::class)->deleteNotIds($this->currentGroups);
		$em->flush();

//		$output->writeln("<info>Imported products and groups</info>");
	}

	protected function createProductGroup(
		EntityManagerInterface $em,
		array $element,
		?ProductGroup $groupParent,
		$imageUrl
	) {
		$code = $element[self::CODE];
		$name = $element[self::NAME];
		$simaCode = $element[self::SIMA_CODE];
		$photo = trim($element[self::PHOTO]) ? $imageUrl . $this->encodeUrlImage(trim($element[self::PHOTO])) : "";

		if(isset($element[self::GROUP_DISCOUNT])){
            $sale = trim($element[self::GROUP_DISCOUNT]);
        } else{
		    $sale = 0;
        }

		$group = $em->getRepository(ProductGroup::class)->findOneBy(["apiId" => $code]);

		if ($group instanceof ProductGroup) {
			$group->setName($name);
			$group->setSimaCode($simaCode);
			$group->setPhoto($photo);
			$group->setSale($sale);
		} else {
			$group = new ProductGroup(
				$code,
				$simaCode,
				$name,
				$photo,
				null,
				$sale
			);

			$em->persist($group);
			$em->flush();
		}

		$group->setParentGroup($groupParent);

		$this->currentGroups[] = $group->getId();

		return $group;
	}

    protected function createProduct(EntityManagerInterface $em, array $element, ?ProductGroup $group, $imageUrl)
    {
        $code = $element[self::CODE];
        $name = $element[self::NAME];
        $simaCode = $element[self::SIMA_CODE];
        $photo = trim($element[self::PHOTO]) ? $imageUrl . $this->encodeUrlImage(trim($element[self::PHOTO])) : "";
        $cost = isset($element[self::COST]) ? str_replace(',', '.', $element[self::COST]):0;
        $rozCost = isset($element[self::ROZ_COST]) ? str_replace(',', '.', $element[self::ROZ_COST]):0;
        $leftCount = (int)$element[self::LEFT_COUNT];
        $description = array_key_exists(self::DESCRIPTION, $element) ? $element[self::DESCRIPTION] : "";
        $textDescription = array_key_exists(self::TEXT_DESCRIPTION, $element) ? $element[self::TEXT_DESCRIPTION] : "";

        $product = $em->getRepository(Product::class)->findOneBy(["apiId" => $code]);

        if ($cost > 0) {
            if ($product instanceof Product) {
                $product->setName($name);
                $product->setSimaCode($simaCode);
                $product->setPhoto($photo);
                $product->setCost($cost);
                $product->setRozCost(
                    $rozCost > 0 ? $rozCost : ($product->getRozCost() != 0 ? $product->getRozCost() : $cost)
                );
                $product->setLeftCount($leftCount);
                $product->setDescription($description);
                $product->setTextDescription($textDescription);
                $product->setOldCost($product->getOldCost() ? $product->getOldCost() : 0);
            } else {
                $product = new Product(
                    $code,
                    $simaCode,
                    $name,
                    $photo,
                    $cost,
                    $rozCost ? $rozCost : $cost,
                    $leftCount,
                    $description,
                    $textDescription
                );

                $em->persist($product);
                $em->flush();
            }
        }

        $product->setProductGroup($group);

        $productGroup = $product->getProductGroup();

        if ($productGroup->getSale() > 0) {
            $product->setOldCost($product->getCost());
            $product->setCost($product->getCost() * (100 - $productGroup->getSale()) / 100);

            $em->persist($product);
            $em->flush();
        }

        $this->currentProducts[] = $product->getId();

        return $product;
    }

	protected function encodeUrlImage($imageStr)
	{
		return str_replace(" ", "%20", $imageStr);
	}
}
