<?php

namespace App\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('id', 'integer', array('required' => false))
			->add('isActive', 'checkbox', array('required' => false))
			->add('isNewPrice', 'checkbox', array('required' => false))
			->add('nomen', 'integer', array('required' => false))
			->add('mark', 'textarea')
			->add('name', 'textarea')
			->add('length', 'integer', array('required' => false))
			->add('width', 'integer', array('required' => false))
			->add('height', 'integer', array('required' => false))
			->add('weight', 'number', array('required' => false))
			->add('volume', 'number', array('required' => false))
			->add('diameterOut', 'integer', array('required' => false))
			->add('diameterIn', 'integer', array('required' => false))
			->add('price', 'integer', array('required' => false))
			->add('description', 'textarea', array('required' => false))
			->add('comments', 'textarea', array('required' => false));
	}


	public function getName()
	{
		return 'product';
	}
}
