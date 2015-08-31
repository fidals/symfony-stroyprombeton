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
			->with('Основные')
				->add('id', null, array('read_only' => true, 'required' => true))
				->add('nomen', null, array('label' => 'Артикул', 'required' => false))
				->add('name', null, array('label' => 'Название', 'required' => true))
				->add('category', 'sonata_type_model', array('label' => 'Родитель', 'required' => false))
				->add('mark', null, array('label' => 'Марка', 'required' => false))
				->add('price', null, array('label' => 'Цена', 'required' => false))
				->add('introtext', 'textarea', array('required' => false))
				->add('isTextPublished', null, array('label' => 'Текст опубликован', 'required' => false))
				->add('text', 'textarea', array('required' => false))
			->end()
			->with('Характеристики')
				->add('length', null, array('label' => 'Длина', 'required' => false))
				->add('width', null, array('label' => 'Ширина', 'required' => false))
				->add('height', null, array('label' => 'Высота', 'required' => false))
				->add('weight', null, array('label' => 'Масса', 'required' => false))
				->add('diameterIn', null, array('label' => 'Диаметр внутр', 'required' => false))
				->add('diameterOut', null, array('label' => 'Диаметр внеш', 'required' => false))
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
			->add('nomen', null, array('label' => 'Артикул'))
			->add('name', null, array('label' => 'Название'))
			->add('category', null, array('label' => 'Родитель'))
			->add('mark', null, array('label' => 'Марка'))
			->add('price', null, array('label' => 'Цена'))
			->add('isActive', null, array('label' => 'Активно'));
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->add('id')
			->add('nomen', null, array('label' => 'Артикул'))
			->addIdentifier('name', null, array('label' => 'Название'))
			->add('category', 'sonata_type_model', array('label' => 'Родитель'))
			->add('mark', null, array('label' => 'Марка'))
			->add('length', null, array('label' => 'Длина'))
			->add('width', null, array('label' => 'Ширина'))
			->add('height', null, array('label' => 'Высота'))
			->add('weight', null, array('label' => 'Масса'))
			->add('diameterIn', null, array('label' => 'Диаметр внутр'))
			->add('diameterOut', null, array('label' => 'Диаметр внеш'))
			->add('price', null, array('label' => 'Цена'))
			->add('isActive', null, array('label' => 'Активно'));
	}
}