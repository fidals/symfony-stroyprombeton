<?php

namespace App\ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => false))
            ->add('phone', 'text', array('required' => false))
            ->add('email', 'email', array('required' => false));
    }

    public function getName()
    {
        return 'feedback';
    }
}
