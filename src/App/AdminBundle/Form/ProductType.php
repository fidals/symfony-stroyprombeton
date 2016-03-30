<?php
namespace App\AdminBundle\Form;

use App\AdminBundle\Form\DataTransformer\ParentTransformer;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProductType.
 */
class ProductType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('id', NumberType::class, array('read_only' => true))
            ->add('introtext', TextType::class, array('required' => false))
            ->add('isTextPublished', CheckboxType::class, array('required' => false))
            ->add('text', CKEditorType::class, array('required' => false, 'config_name' => 'standard'))
            ->add('price', NumberType::class)
            ->add('datePriceUpdated', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                ), ))
            ->add('length', NumberType::class, array('required' => false))
            ->add('weight', NumberType::class, array('required' => false))
            ->add('height', NumberType::class, array('required' => false))
            ->add('width', NumberType::class, array('required' => false))
            ->add('volume', NumberType::class, array('required' => false))
            ->add('diameterIn', NumberType::class, array('required' => false))
            ->add('diameterOut', NumberType::class, array('required' => false))
            ->add('mark', TextType::class)
            ->add('category', TextType::class, array('required' => true))
            ->add('title', TextType::class, array('required' => false))
            ->add('isActive', CheckboxType::class, array('required' => false))
            ->add('H1', TextType::class, array('required' => false))
            ->add('keywords', TextType::class, array('required' => false))
            ->add('description', TextType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->add('delete', ButtonType::class);

        $builder->get('category')
            ->addModelTransformer(new ParentTransformer($this->entityManager));
    }
}
