<?php

namespace App\AdminBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TerritoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', NumberType::class, array('read_only' => true))
            ->add('translitName', TextType::class, array('required' => false))
            ->add('text', CKEditorType::class, array('config_name' => 'standard'))
            ->add('title', TextType::class, array('required' => false))
            ->add('isActive', CheckboxType::class, array('required' => false))
            ->add('H1', TextType::class, array('required' => false))
            ->add('keywords', TextType::class, array('required' => false))
            ->add('description', TextType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->add('delete', ButtonType::class);
    }
}
