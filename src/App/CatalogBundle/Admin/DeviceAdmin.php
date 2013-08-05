<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для Products
 *
 * Class DeviceAdmin
 * @package App\MainBundle\Admin
 */
class DeviceAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Название'))
            ->add('series','sonata_type_model',array('label'=>'Серия'))
            ->add('deviceType', null, array('label' => 'Тип устройства'))
            ->add('products', null, array('label' => 'продукты'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Название'))
            ->add('series',null,array('label'=>'Серия'))
            ->add('deviceType', null, array('label' => 'Тип устройства'));
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('series',null,array('label'=>'Серия'))
            ->add('deviceType', null, array('label' => 'Тип устройства'));
        ;
    }
}
