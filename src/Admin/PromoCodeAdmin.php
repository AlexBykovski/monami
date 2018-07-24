<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;

class PromoCodeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('code', TextType::class, [
            'label' => 'Код',
            'constraints' => [
                new NotNull(['message' => 'Введите код']),
            ]
        ]);
        $formMapper->add('discount', MoneyType::class, [
            'label' => 'Скидка',
            'constraints' => [
                new NotNull(['message' => 'Введите скидку']),
            ],
            'scale' => 2,
            'currency' => false
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('code', 'text', ['label' => 'Код', 'sortable' => false]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete']);
    }
}