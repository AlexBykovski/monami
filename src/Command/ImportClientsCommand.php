<?php

namespace App\Command;

use App\Entity\Basket;
use App\Entity\Client;
use App\Entity\ImportDetail;
use App\Entity\Manager;
use App\Entity\User;
use App\Import\XMLDataImporter;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportClientsCommand extends ContainerAwareCommand
{
    const ATTRIBUTES = "@attributes";
    const ELEMENT = "Элемент";
    const CODE = "Код";
    const CONTR_AGENT = "Контрагент";
    const LOGIN = "Логин";
    const PASSWORD = "Пароль";
    const EMAIL = "email";
    const PHONES = "телефоны";
    const MANAGER = "Менеджер";
    const CODE_MANAGER = "КодМенеджера";
    const DISCOUNT = "Скидка";

    /** @var EntityManagerInterface $em */
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:import:clients')
            ->setDescription('Import clients');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var XMLDataImporter $importer */
        $importer = $this->getContainer()->get("app.import.import_xml_data");
        /** @var EntityManagerInterface $em */
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        /** @var ImportDetail $importDetail */
        $importDetail = $this->em->getRepository(ImportDetail::class)->findOneBy(["nameCode" => "clients"]);
        $importDetail->setLastUpdateStatus(ImportDetail::STATUS_SUCCESS);
        $importDetail->setLastUpdatedAt(new DateTime());

        $data = $importer->importData($importDetail);

        if(!is_array($data) || !array_key_exists(self::ELEMENT, $data) || !count($data[self::ELEMENT])){
//            $output->writeln("<comment>Empty import file!</comment>");

            return false;
        }

        //import groups and products
        foreach (array_reverse($data[self::ELEMENT]) as $index => $datum){
//            $output->writeln($index  . '/' . count($data[self::ELEMENT]));
            $element = $datum[self::ATTRIBUTES];

            var_dump($element);

            if(!is_array($element)){

                continue;
            }

            $this->createClient($element, $importDetail->getImagesUrl());
        }

        $output->writeln("<info>Imported managers</info>");
    }

    protected function createClient(array $element, $imageUrl)
    {
        $code = $element[self::CODE];
        $contrAgent = $element[self::CONTR_AGENT];
        $email = $element[self::EMAIL];
        $phones = $element[self::PHONES];
        $login = $element[self::LOGIN];

        $disc = str_replace(",", ".", $element[self::DISCOUNT]);
        $discount = (float)$disc;

        $manager = $this->getManager($element);

        $client = $this->em->getRepository(Client::class)->findOneBy(["apiId" => $code]);

        if(!$client){
            $client = $this->em->getRepository(Client::class)->findOneBy(["username" => $login]);
        }

        if($client instanceof Client){
            $client->setContrAgent($contrAgent);
            $client->setEmail($email);
            $client->setPhone($phones);
            $client->setUsername($login);
            $client->setManager($manager);
            $client->setEmailCanonical($login);
            $client->addRole(User::ROLE_CLIENT);
            $client->setDiscount($discount);

            if(!$client->getBasket()){
                $cart = new Basket($client);

                $client->setBasket($cart);

                $this->em->persist($cart);
            }
        }
        else{
            $client = new Client(
                $code,
                $phones,
                $email,
                $login,
                $manager,
                $contrAgent,
				$discount
            );

            $this->em->persist($client);
        }

        $password = $this->getDecodePassword($element[self::PASSWORD], $client);
        $client->setPassword($password);

        $this->em->flush();

        return $client;
    }

    /**
     * @param array $element
     *
     * @return null|Manager
     */
    protected function getManager(array $element)
    {
        $managerFullName = $element[self::MANAGER];
        $managerCode = $element[self::CODE_MANAGER];

        $manager = $this->em->getRepository(Manager::class)->findOneBy(["apiId" => $managerCode]);

        if($manager instanceof Manager){
            return $manager;
        }

        return $this->em->getRepository(Manager::class)->findOneBy(["fullName" => $managerFullName]);
    }

    protected function getDecodePassword(string $password, Client $client)
    {
        /** @var UserPasswordEncoderInterface $encoder */
        $encoder = $this->getContainer()->get('security.password_encoder');

        return  $encoder->encodePassword($client, $password);
    }
}
