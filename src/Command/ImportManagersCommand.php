<?php

namespace App\Command;

use App\Entity\ImportDetail;
use App\Entity\Manager;
use App\Import\XMLDataImporter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportManagersCommand extends ContainerAwareCommand
{
    const ATTRIBUTES = "@attributes";
    const ELEMENT = "Элемент";
    const CODE = "Код";
    const FULL_NAME = "Менеджер";
    const EMAIL = "email";
    const PHONE = "Телефон";
    const PHOTO = "Фото";

    protected function configure()
    {
        $this
            ->setName('app:import:managers')
            ->setDescription('Import managers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var XMLDataImporter $importer */
        $importer = $this->getContainer()->get("app.import.import_xml_data");
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        /** @var ImportDetail $importDetail */
        $importDetail = $em->getRepository(ImportDetail::class)->findOneBy(["nameCode" => "manager"]);
        $importDetail->setLastUpdateStatus(ImportDetail::STATUS_SUCCESS);
        $importDetail->setLastUpdatedAt(new DateTime());

        $data = $importer->importData($importDetail);

        if(!is_array($data) || !array_key_exists(self::ELEMENT, $data) || !count($data[self::ELEMENT])){
            //$output->writeln("<comment>Empty import file!</comment>");
            return false;
        }

        //import groups and products
        foreach ($data[self::ELEMENT] as $index => $datum){
            $element = $datum[self::ATTRIBUTES];

            if(!is_array($element)){

                continue;
            }

            $this->createManager($em, $element, $importDetail->getImagesUrl());
        }

        $em->flush();

        //$output->writeln("<info>Imported managers</info>");
    }

    protected function createManager(EntityManagerInterface $em, array $element, $imageUrl)
    {
        $code = $element[self::CODE];
        $fullName = $element[self::FULL_NAME];
        $photo = $element[self::PHOTO] ? $imageUrl . $element[self::PHOTO] : "";
        $email = $element[self::EMAIL];
        $phone = $element[self::PHONE];

        $manager = $em->getRepository(Manager::class)->findOneBy(["apiId" => $code]);

        if($manager instanceof Manager){
            $manager->setFullName($fullName);
            $manager->setImage($photo);
            $manager->setEmail($email);
            $manager->setPhone($phone);
        }
        else{
            $manager = new Manager(
                $photo,
                $fullName,
                $code,
                $phone,
                $email
            );

            $em->persist($manager);
        }

        return $manager;
    }
}
