<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для Trademark
 *
 * Class TrademarkPageAdmin
 * @package App\CatalogBundle\Admin
 */
class TrademarkPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('alias', null, array('label' => 'Название'))
            ->add('title', null, array('label' => 'Title')) ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id',null)
            ->add('alias', null, array('label' => 'Название'))
            ->add('title', null, array('label' => 'Title'));

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id',null)
            ->addIdentifier('alias', null, array('label' => 'Название'))
            ->add('title', null, array('label' => 'Title'))
        ;
    }
}
