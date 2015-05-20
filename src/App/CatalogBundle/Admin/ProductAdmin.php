<?php
namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->with('Основные свойства')
				->add('id', null, array('read_only' => true, 'required' => false))
				->add('isNewPrice', null, array('required' => false))
				->add('nomen', null, array('required' => false))
				->add('mark', null, array('required' => true))
				->add('comments', null, array('required' => false))
				->add('name', null, array('required' => false))
				->add('sectionId', null, array('label' => 'section_id', 'required' => false))
				->add('category', 'sonata_type_model', array('required' => false))
				->add('length', null, array('required' => false))
				->add('width', null, array('required' => false))
				->add('height', null, array('required' => false))
				->add('weight', null, array('required' => false))
				->add('volume', null, array('required' => false))
				->add('diameterOut', null, array('label' => 'diameter_out', 'required' => false))
				->add('diameterIn', null, array('label' => 'diameter_in', 'required' => false))
				->add('price', null, array('required' => false))
				->add('text', null, array('required' => false))
				->add('isHavePhoto', null, array('label' => 'is_have_photo', 'required' => false))
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
			->add('isActive', null, array('label' => 'is_active'))
			->add('isNewPrice')
			->add('nomen')
			->add('mark')
			->add('name')
			->add('sectionId', null, array('label' => 'section_id'))
			->add('category')
			->add('length')
			->add('width')
			->add('height')
			->add('weight')
			->add('volume')
			->add('diameterOut', null, array('label' => 'diameter_out'))
			->add('diameterIn', null, array('label' => 'diameter_in'))
			->add('price')
			->add('description')
			->add('isHavePhoto', null, array('label' => 'is_have_photo'))
			->add('comments');
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->add('id')
			->add('isActive', null, array('label' => 'is_active'))
			->add('isNewPrice')
			->addIdentifier('nomen')
			->add('mark')
			->addIdentifier('name')
			->add('sectionId', null, array('label' => 'section_id'))
			->add('category')
			->add('length')
			->add('width')
			->add('height')
			->add('weight')
			->add('volume')
			->add('diameterOut', null, array('label' => 'diameter_out'))
			->add('diameterIn', null, array('label' => 'diameter_in'))
			->add('price')
			->add('description')
			->add('isHavePhoto', null, array('label' => 'is_have_photo'))
			->add('comments');
	}
}