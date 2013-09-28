<?php

namespace App\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('person', 'text')
            ->add('phone', 'text')
            ->add('email', 'email')
            ->add('deliveryAdress', 'textarea')
            ->add('comment', 'textarea', array('required' => false));
    }

    
    public function getName()
    {
        return 'order';
    }
}
