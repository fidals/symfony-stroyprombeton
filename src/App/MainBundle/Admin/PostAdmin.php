<?php
namespace App\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class PostAdmin extends Admin
{
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->with('Основные')
				->add('id', null, array('read_only' => true, 'required' => true))
				->add('name', null, array('label' => 'Название', 'required' => true))
				->add('date', 'sonata_type_datetime_picker', array(
					'label'           => 'Дата',
					'required'        => true,
					'format'          => 'dd/MM/yyyy HH:mm',
					'dp_side_by_side' => true,
					'dp_use_current'  => true,
					'dp_use_seconds'  => false,
				))
				->add('introText', 'textarea', array('required' => false))
				->add('text', 'textarea', array('required' => false))
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
			->add('isActive', null, array('label' => 'Активно'));
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->add('id')
			->addIdentifier('name', null, array('label' => 'Название'))
			->add('date', null, array('label' => 'Дата'))
			->add('isActive', null, array('label' => 'Активно'));
	}
}