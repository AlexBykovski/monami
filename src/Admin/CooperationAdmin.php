<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CooperationAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('contacts', CKEditorType::class, [
            'label' => 'С кем мы работаем?',
            'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor'),
            'config' => array(
                'toolbar' => 'full',
            )
        ])->getFormBuilder()->getForm();
        $formMapper->add('requisites', CKEditorType::class, ['label' => 'Режим работы!',
            'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor'),]);
        $formMapper->add('map', CKEditorType::class, ['label' => 'Наши условия!',
            'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor'),]);
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
