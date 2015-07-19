<?php

namespace App\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PriceListBookingType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('person', 'text', array('required' => false))
			->add('phone', 'text', array('required' => true))
			->add('email', 'email', array('required' => true))
			->add('company', 'text', array('required' => true))
			->add('city', 'text', array('required' => true))
			->add('activity', 'choice',
				array(
					'choices' => array(
						'Подрядная строительная организация' => 'Подрядная строительная организация',
						'Производство строительных материалов' => 'Производство строительных материалов',
						'Проектная организация' => 'Проектная организация',
						'Заказчик' => 'Заказчик',
						'Оптовая торговля' => 'Оптовая торговля'
					),
					'required' => true
				)
			)
			->add('deliveryAddress', 'textarea', array('required' => false))
			->add('site', 'textarea', array('required' => false))
			->add('comment', 'textarea', array('required' => false));
	}

	public function getName()
	{
		return 'price_list_booking';
	}
}
