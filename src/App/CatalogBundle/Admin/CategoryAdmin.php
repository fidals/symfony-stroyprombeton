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
            ->add('coefficient',null, array('label' => 'коэффициент','required' => true))
            ->add('parent', 'sonata_type_model', array('label' => 'Категория','required' => false))
            ->add('description', null, array('label' => 'описание'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('label' => 'Название'))
            ->add('title', null, array('required' => false))
            ->add('coefficient',null, array('label' => 'коэффициент'))
            ->add('parent', null, array('label' => 'Категория'))

        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name', null, array('label' => 'Название'))
            ->add('title', null, array('required' => false))
            ->add('coefficient',null, array('label' => 'коэффициент'))
            ->add('parent', null, array('label' => 'Категория'))

        ;
    }

}