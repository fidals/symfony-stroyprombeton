<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для DevicePage
 *
 * Class DevicePageAdmin
 * @package App\MainBundle\Admin
 */
class DevicePageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('series', null, array('label' => 'Серия'))
            ->add('devicetype', null, array('label' => 'Девайс'))
            ->add('name', null, array('label' => 'Название'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('series', null, array('label' => 'Серия'))
            ->add('devicetype', null, array('label' => 'Девайс'))
            ->add('name', null, array('label' => 'Название'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('series', null, array('label' => 'Серия'))
            ->add('devicetype', null, array('label' => 'Девайс'));

    }
}
