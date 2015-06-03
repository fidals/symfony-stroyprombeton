<?php
namespace App\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class StaticPageAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->with('Основные свойства')
				->add('id', null, array('read_only' => true, 'required' => false))
				->add('name', null, array('required' => false))
				->add('introText', null, array('required' => false))
				->add('menuTitle', null, array('required' => true))
				->add('alias', null, array('required' => false))
				->add('text', null, array('required' => false))
				->add('isActive', null, array('label' => 'is_active', 'required' => false))
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
			->add('name')
			->add('isActive');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id')
			->add('name')
			->add('isActive');
	}

	// Валидация происходит в "validate" методе модели
	public function validate(ErrorElement $errorElement, $object)
	{
		$errorElement->assertCallback(array('validate'));
	}
}