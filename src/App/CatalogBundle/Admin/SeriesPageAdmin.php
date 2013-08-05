<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * Настройки SonataAdmin для Series
 *
 * Class SeriesPageAdmin
 * @package App\CatalogBundle\Admin
 */
class SeriesPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
             ->add('trademark', null, array('label' => 'Марка'))
            ->add('name', null, array('label' => 'Название'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('trademark', null, array('label' => 'Марка'))
            ->add('name', null, array('label' => 'Название'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('trademark', null, array('label' => 'Марка'))
        ;
    }
}
