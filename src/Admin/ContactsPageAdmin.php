<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactsPageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('contacts', CKEditorType::class, [
            'label' => 'Контакты',
            'attr' => ['style' => 'width:100%;height:200px;', 'class'=>'ckeditor'],
            'config' => array(
                'toolbar' => array(
                    array(
                        'name' => 'links',
                        'items' => array('Link', 'Unlink'),
                    ),
                    array(
                        'name' => 'insert',
                        'items' => array('Image'),
                    ),
                )
            )
        ]);
        $formMapper->add('requisites', CKEditorType::class, ['label' => 'Реквизиты', 'attr' => ['style' => 'width:100%;height:200px;', 'class'=>'ckeditor']]);
        $formMapper->add('map', TextareaType::class, ['label' => 'Схема проезда', 'attr' => ['style' => 'width:100%;height:200px;', 'class'=>'ckeditor']]);
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
