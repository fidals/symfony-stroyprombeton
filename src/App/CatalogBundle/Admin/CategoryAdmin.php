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
			->with('Основные')
				->add('id', null, array('read_only' => true, 'required' => true))
				->add('name', null, array('label' => 'Название', 'required' => true))
				->add('parent', 'sonata_type_model', array('label' => 'Родитель', 'required' => false))
				->add('linkToStkMetal', null, array('required' => false))
				->add('isTextPublished', null, array('label' => 'Текст опубликован', 'required' => false))
				->add('text', null, array('required' => false))
			->end()
			->with('SEO')
				->add('isActive', null, array('label' => 'Активно', 'required' => false))
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
			->add('name', null, array('label' => 'Название'))
			->add('parent', null, array('label' => 'Родитель'))
			->add('mark', null, array('label' => 'Марка'))
			->add('isActive', null, array('label' => 'Активно'));
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->add('id')
			->addIdentifier('name', null, array('label' => 'Название'))
			->add('parent', null, array('label' => 'Родитель'))
			->add('mark', null, array('label' => 'Марка'))
			->add('isActive', null, array('label' => 'Активно'));
	}

	// Валидация происходит в "validate" методе модели
	public function validate(ErrorElement $errorElement, $object)
	{
		$errorElement->assertCallback(array('validate'));
	}
}