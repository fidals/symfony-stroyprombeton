<?php

namespace App\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class OrderDrawingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', TextType::class, array(
                'constraints' => new Length(array(
                    'min' => 3,
                    'minMessage' => 'Длина имени должна быть не менее 3 символов',
                )),
            ))
            ->add('phone', TextType::class, array(
                'constraints' => new Length(array(
                    'min' => 16,
                    'minMessage' => 'В телефоном номере должно быть 11 цифр',
                )),
            ))
            ->add('email', EmailType::class, array('required' => true))
            ->add('comment', TextareaType::class, array('required' => false))
            ->add('files', FileType::class, array(
                'required' => false,
                'multiple' => true,
                'constraints' => array(
                    new Count(array('max' => 10)),
                    new All(array(
                        'constraints' => array(
                            new File(array(
                                'maxSize' => '25550000', // ~25 Mb;
                                'maxSizeMessage' => 'Размер файла не должен превышать 25 Мб',
                            )),
                        ),
                    )),
                ),
            ));
    }
}
