<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для Products
 *
 * Class TrademarkAdmin
 * @package App\MainBundle\Admin
 */
class TrademarkAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('alias', null, array('label' => 'Alias'))
            ->add('title', null, array('label' => 'Title'));

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('alias', null, array('label' => 'Alias'))
            ->add('title', null, array('label' => 'Title'));
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('alias', null, array('label' => 'Alias'))
            ->add('title', null, array('label' => 'Title'));
        ;
    }
}
