<?php
namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class CategoryAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->with('Основные свойства')
				->add('id', null, array('read_only' => true, 'required' => false))
				->add('nomen', null, array('required' => false))
				->add('parent', 'sonata_type_model', array('label' => 'Родительская категория', 'required' => false))
				->add('name', null, array('label' => 'Название', 'required' => true))
				->add('alias', null, array('required' => false))
				->add('mark', null, array('required' => false))
				->add('order', null, array('label' => 'ord', 'required' => false))
				->add('coefficient', null, array('required' => true, 'data' => '1.1'))
				->add('text', null, array('required' => false))
				->add('isActive', null, array('label' => 'is_active', 'required' => false))
				->add('file', 'file', array('label' => 'фото', 'required' => false))
			->end()
			->with('SEO')
				->add('title', null, array('required' => false))
				->add('h1', null, array('required' => false))
				->add('keywords', null, array('required' => false))
				->add('description', 'textarea', array('required' => false))
			->end();
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
			->add('id')
			->add('nomen')
			->add('parent', null, array('label' => 'Родительская категория'))
			->add('name', null, array('label' => 'Название'))
			->add('mark')
			->add('order', null, array('label' => 'ord'))
			->add('isActive', null, array('label' => 'is_active'));
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id')
			->addIdentifier('nomen')
			->add('parent', null, array('label' => 'parent_id'))
			->addIdentifier('name', null, array('label' => 'Название'))
			->addIdentifier('title')
			->add('alias')
			->add('mark')
			->add('order', null, array('label' => 'ord'))
			->add('coefficient')
			->add('isActive', null, array('label' => 'is_active'))
			->add('description');
	}

	public function postPersist($category)
	{
		$this->saveFile($category);
	}

	public function preUpdate($category)
	{
		$this->saveFile($category);
	}

	public function saveFile($category)
	{
		$basepath = $this->getRequest()->getBasePath();
		$category->upload($basepath);
	}

	public function preRemove($category)
	{
		$category->rmUploaded();
	}

	// Валидация происходит в "validate" методе модели
	public function validate(ErrorElement $errorElement, $object)
	{
		$errorElement->assertCallback(array('validate'));
	}
}