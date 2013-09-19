<?php
namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Название','required' => true))
            ->add('title', null, array('required' => false))
            ->add('coefficient',null, array('label' => 'коэфициент','required' => true))
            ->add('parent', 'sonata_type_model', array('label' => 'Родитель','required' => false))
            ->add('description', null, array('required' => false))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('required' => true))
            ->add('parent', null, array('label' => 'Родитель'))
            ->add('isActive', null, array('required' => false))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('required' => false))
            ->add('title')
            ->add('parent', null, array('label' => 'Родитель'))
            ->add('isActive', null, array('required' => false))
        ;
    }

}