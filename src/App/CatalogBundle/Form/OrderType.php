<?php

namespace App\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Форма заказа
 *
 * Class OrderType
 * @package App\CatalogBundle\Form
 */
class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('person', 'text', array('required' => false))
            ->add('phone', 'text')
            ->add('email', 'email')
            ->add('deliveryAdress', 'textarea', array('required' => false))
            ->add('comment', 'textarea', array('required' => false));
    }


    public function getName()
    {
        return 'order';
    }
}
