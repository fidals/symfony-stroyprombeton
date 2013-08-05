<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для CategoryPage
 *
 * Class CategoryPageAdmin
 * @package App\CatalogBundle\Admin
 */
class CategoryPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('parent', 'sonata_type_model', array('label' => 'Родитель'))
            ->add('name', null, array('label' => 'Название'))
            ->add('title', null, array('label' => 'Title'))
            ->add('order', null, array('label' => 'Ord'))
            ->add('isActive', null, array('label' => 'Доступно'))
            ->add('description', null, array('label' => 'Описание'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('parent', null, array('label' => 'Родитель'))
            ->add('name', null, array('label' => 'Название'))
            ->add('title', null, array('label' => 'Title'))
            ->add('order', null, array('label' => 'Ord'))
            ->add('isActive', null, array('label' => 'Доступно'))
            ->add('description', null, array('label' => 'Описание'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('parent', null, array('label' => 'Родитель'))
            ->add('title', null, array('label' => 'Title'))
            ->add('order', null, array('label' => 'Ord'))
            ->add('isActive', null, array('label' => 'Доступно'))
            ->add('description', null, array('label' => 'Описание'));
    }
}
