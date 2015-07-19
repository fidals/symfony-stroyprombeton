<?php

namespace App\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('person', 'text', array('required' => true))
			->add('phone', 'text', array('required' => true))
			->add('email', 'email', array('required' => true))
			->add('company', 'text', array('required' => true))
			->add('deliveryAddress', 'textarea', array('required' => false))
			->add('comment', 'textarea', array('required' => false));
	}

	public function getName()
	{
		return 'order';
	}
}
