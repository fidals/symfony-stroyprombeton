<?php
namespace App\CatalogBundle\Admin;

use App\CatalogBundle\Controller\CatalogController;
use App\CatalogBundle\Entity\Repository\ProductRepository;
use EntityManager51ef8ed0363a3_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class ProductAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('isNewPrice', null, array('required' => false))
            ->add('nomen', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->add('name', null, array('required' => false))
            ->add('sectionId', null, array('label' => 'section_id','required' => false))
            ->add('category', 'sonata_type_model', array('required' => false))
            ->add('length', null, array('required' => false))
            ->add('width', null, array('required' => false))
            ->add('height', null, array('required' => false))
            ->add('weight', null, array('required' => false))
            ->add('volume', null, array('required' => false))
            ->add('diameterOut', null, array('label' => 'diameter_out','required' => false))
            ->add('diameterIn', null, array('label' => 'diameter_in','required' => false))
            ->add('price', null, array('required' => false))
            ->add('description', null, array('required' => false))
            ->add('isHavePhoto', null, array('label' => 'is_have_photo','required' => false))
            ->add('comments', null, array('required' => false))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('isNewPrice', null, array('required' => false))
            ->add('nomen', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->add('name', null, array('required' => false))
            ->add('sectionId', null, array('label' => 'section_id','required' => false))
            ->add('category', null, array('required' => false))
            ->add('length', null, array('required' => false))
            ->add('width', null, array('required' => false))
            ->add('height', null, array('required' => false))
            ->add('weight', null, array('required' => false))
            ->add('volume', null, array('required' => false))
            ->add('diameterOut', null, array('label' => 'diameter_out','required' => false))
            ->add('diameterIn', null, array('label' => 'diameter_in','required' => false))
            ->add('price', null, array('required' => false))
            ->add('description', null, array('required' => false))
            ->add('isHavePhoto', null, array('label' => 'is_have_photo','required' => false))
            ->add('comments', null, array('required' => false))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('isNewPrice', null, array('required' => false))
            ->addIdentifier('nomen', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->addIdentifier('name', null, array('required' => false))
            ->add('sectionId', null, array('label' => 'section_id','required' => false))
            ->add('category', null, array('required' => false))
            ->add('length', null, array('required' => false))
            ->add('width', null, array('required' => false))
            ->add('height', null, array('required' => false))
            ->add('weight', null, array('required' => false))
            ->add('volume', null, array('required' => false))
            ->add('diameterOut', null, array('label' => 'diameter_out','required' => false))
            ->add('diameterIn', null, array('label' => 'diameter_in','required' => false))
            ->add('price', null, array('required' => false))
            ->add('description', null, array('required' => false))
            ->add('isHavePhoto', null, array('label' => 'is_have_photo','required' => false))
            ->add('comments', null, array('required' => false))

        ;
    }

}