<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AboutPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('contacts', CKEditorType::class, [
            'label' => 'ООО "Мон Ами" поставщик сувенирной продукции и товаров для дома:',
            'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor'),
        ])->getFormBuilder()->getForm();

        $formMapper->add('requisites', CKEditorType::class, [
            'label' => 'Сувениры оптом на каждый сезон:',
            'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor')]);

        $formMapper->add('map', CKEditorType::class, [
            'label' => 'Как мы это делаем?',
            'attr' => ['style' => 'width:100%;height:200px;', 'class'=>'ckeditor']]);

        $formMapper->add('timeWork', CKEditorType::class, [
            'label' => 'Время работы',
            'attr' => ['style' => 'width:100%;height:100px;', 'class'=>'ckeditor']]);
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', 'text', ['label' => 'ID', 'sortable' => false])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }
}
