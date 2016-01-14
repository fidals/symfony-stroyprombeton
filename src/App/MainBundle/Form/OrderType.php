<?php

namespace App\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class OrderType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('person', 'text', array(
				'constraints' => new Length(array(
					'min' => 3,
					'minMessage' => 'Длина имени должна быть не менее 3 символов'
				))
			))
			->add('phone', 'text', array(
				'constraints' => new Length(array(
					'min' => 16,
					'minMessage' => 'В телефоном номере должно быть 11 цифр'
				))
			))
			->add('email', 'email', array('required' => true))
			->add('company', 'text', array('required' => true))
			->add('deliveryAddress', 'textarea', array('required' => false))
			->add('comment', 'textarea', array('required' => false))
			->add('files', 'file', array(
				'required' => false,
				'multiple' => true,
				'constraints' => array(
					new Count(array('max' => 10)),
					new All(array(
		                'constraints' => array(
							new File(array(
								'maxSize' => '25550000', // ~25 Mb;
								'maxSizeMessage' => 'Размер файла не должен превышать 50 Мб'
							))
		                )
		            ))
				),
			));
	}

	public function getName()
	{
		return 'order';
	}
}
