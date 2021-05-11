<?php

namespace App\Admin;

use App\Upload\FileUpload;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Gaufrette\File;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ManagersPageAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper->add('image', CKEditorType::class, ['label' => 'Фото']);
		$formMapper->add('fullname', CKEditorType::class, ['label' => 'Полное имя']);
		$formMapper->add('phone', null, ['label' => 'Телефоны']);
		$formMapper->add('email', TextareaType::class, ['label' => 'E-Mail']);
	}

//	public function prePersist($page) {
//		$this->manageEmbeddedImageAdmins($page);
//	}
//
//	public function preUpdate($page) {
//		$this->manageEmbeddedImageAdmins($page);
//	}
//
//
//	private function manageEmbeddedImageAdmins($page) {
//
//		/** @var Image $image */
//		$image = $page->getImage();
//
//		if ($image) {
//
//				$file = null;
//
//				/** @var UploadedFile $file */
//				foreach ($this->request->files as $fileTemp) {
//					$file = $fileTemp['image'];
//				}
//
//				File::class
//
//				move_uploaded_file($image, "/monami/uploads/" . time() . $file->getClientOriginalName());
//		}
//	}
//

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper->addIdentifier('id', 'text', ['label' => 'ID', 'sortable' => false])
			->addIdentifier('fullname', 'text', ['label' => 'ID', 'sortable' => false])
			->add('_action', null, [
				'actions' => [
					'edit' => [],
					'delete' => [],
				]
			]);
	}

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->clearExcept(['list', 'edit', 'create']);
	}
}
