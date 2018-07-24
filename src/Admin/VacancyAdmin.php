<?php

namespace App\Admin;

use App\Form\Type\VacancyBlockForm;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;

class VacancyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class, ['label' => 'Название']);
        $formMapper->add('vacancyBlocks', CollectionType::class, [
            'entry_type' => VacancyBlockForm::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => true
        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', 'text', ['label' => 'Название', 'sortable' => false])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }

    public function preUpdate($vacancy)
    {
        $vacancyBlocks = $this->getForm()->get("vacancyBlocks");

        /** @var Form $block */
        foreach ($vacancyBlocks as $block){
            $block->getData()->setVacancy($vacancy);
        }
    }

    public function prePersist($vacancy)
    {
        $vacancyBlocks = $this->getForm()->get("vacancyBlocks");

        /** @var Form $block */
        foreach ($vacancyBlocks as $block){
            $block->getData()->setVacancy($vacancy);
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit', 'create', 'delete']);
    }
}