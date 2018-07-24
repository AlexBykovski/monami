<?php

namespace App\Admin;

use App\Entity\LinkImage;
use App\Entity\MainPage;
use App\Form\Type\LinkImageForm;
use App\Upload\FileUpload;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MainPageAdmin extends AbstractAdmin
{
    protected $uploader = null;

    public function __construct(string $code, string $class, string $baseControllerName, FileUpload $uploader)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->uploader = $uploader;

        $this->uploader->setFolder(FileUpload::MAIN_PAGE);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('linkImages', CollectionType::class, [
            'entry_type' => LinkImageForm::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false
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

//    public function preUpdate($vacancy)
//    {
//        $vacancyBlocks = $this->getForm()->get("linkImages");
//
//        /** @var Form $block */
//        foreach ($vacancyBlocks as $block){
//            $block->getData()->setVacancy($vacancy);
//        }
//    }
//
//    public function prePersist($vacancy)
//    {
//        $vacancyBlocks = $this->getForm()->get("vacancyBlocks");
//
//        /** @var Form $block */
//        foreach ($vacancyBlocks as $block){
//            $block->getData()->setVacancy($vacancy);
//        }
//    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'edit']);
    }

    public function preUpdate($mainPage)
    {
        $this->uploadFiles($this->getForm(), $mainPage);
    }

    protected function uploadFiles(Form $form, MainPage $mainPage){
        $linkImages = $form->get('linkImages');
        $newMainPageLinks = new ArrayCollection();

        /** @var Form $linkImages */
        foreach($linkImages as $linkImageForm){
            /** @var LinkImage $linkImage */
            $linkImage = $linkImageForm->getData();

            $file = $linkImageForm->get("image")->getData();

            if($file instanceof UploadedFile) {
                $path = $this->uploader->upload($file);
                $linkImage->setImage($path);
            }

            $newMainPageLinks->add($linkImage);
        }

        $mainPage->setLinkImages($newMainPageLinks);
    }
}