<?php

namespace App\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('id', 'integer', array('required' => false))
			->add('nomen', 'integer', array('required' => false))
			->add('name', 'textarea', array('required' => false))
			->add('title', 'textarea')
			->add('mark', 'textarea', array('required' => false))
			->add('order', 'integer')
			->add('isActive', 'checkbox', array('required' => false))
			->add('description', 'textarea', array('required' => false))
			->add('photoId', 'integer', array('required' => false));
	}


	public function getName()
	{
		return 'category';
	}
}
