<?php

namespace App\AdminBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', NumberType::class, array('read_only' => true))
            ->add('alias', TextType::class, array('required' => false))
            ->add('text', CKEditorType::class, array('config_name' => 'standard'))
            ->add('territory', EntityType::class, array(
                'class' => 'AppMainBundle:Territory',
                'choice_label' => 'name',
                'required' => false,
            ))
            ->add('title', TextType::class, array('required' => false))
            ->add('isActive', CheckboxType::class, array('required' => false))
            ->add('H1', TextType::class, array('required' => false))
            ->add('keywords', TextType::class, array('required' => false))
            ->add('description', TextType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->add('delete', ButtonType::class);
    }
}
