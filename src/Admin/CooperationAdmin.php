<?php

namespace App\Admin;

use App\Entity\Cooperation;
use App\Entity\CooperationBlock;
use App\Form\Type\CooperationBlockForm;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Form;

class CooperationAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('schedule', CKEditorType::class, ['label' => 'Режим работы']);
        $formMapper->add('blocks', CollectionType::class, [
            'entry_type' => CooperationBlockForm::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => true
        ]);
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

    public function preUpdate($cooperation)
    {
        $this->setCooperationBeforeFlush($cooperation);
    }

    public function prePersist($cooperation)
    {
        $this->setCooperationBeforeFlush($cooperation);
    }

    protected function setCooperationBeforeFlush(Cooperation $cooperation)
    {
        $cooperationBlocks = $this->getForm()->get("blocks");

        /** @var Form $block */
        foreach ($cooperationBlocks as $block){
            /** @var CooperationBlock $cooperationBlock */
            $cooperationBlock = $block->getData();

            $cooperationBlock->setCooperation($cooperation);
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }
}