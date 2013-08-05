<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для Products
 *
 * Class ProductAdmin
 * @package App\MainBundle\Admin
 */
class ProductAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('category', 'sonata_type_model', array('label' => 'категория'))
            ->add('name', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'описание'))
            ->add('file', 'file', array('label' => 'фото','required'=>false))
            ->add('price', null, array('label' => 'Цена'))
            ->add('devices',null, array('label' => 'Устройства','required'=>false))
            ->add('monthViews', null, array('label' => 'количество просмотров'))
            ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category', null, array('label' => 'Категория'))
            ->add('name', null, array('label' => 'Название'))
            ->add('price', null, array('label' => 'Цена'))
            ->add('devices', null, array('label' => 'Устройства'));
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('category', null, array('label' => 'Категория'))
            ->add('description', null, array('label' => 'описание'))
            ->add('price', null, array('label' => 'Цена'))
            ->addIdentifier('devices', null, array('label' => 'Устройства'))
            ->add('monthViews', null, array('label' => 'количество просмотреов'));
        ;
    }
    public function prePersist($product) {
        $this->saveFile($product);
    }

    public function preUpdate($product) {
        $this->saveFile($product);
    }

    public function saveFile($product) {
        $basepath = $this->getRequest()->getBasePath();
        $product->upload($basepath);
    }
}
