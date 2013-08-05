<?php

namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для ProductPage
 *
 * Class ProductPageAdmin
 * @package App\CatalogBundle\Admin
 */
class ProductPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
           ->add('category', null, array('label' => 'Категория','required'=>true))
            ->add('device', null, array(  'expanded'=>true,
                'class' =>'App\CatalogBundle\Entity\Device',
                'multiple'=>true,'required'=>false,'label' => 'Девайс'))/*'sonata_type_model', array('label' => 'Девайсы','by_reference' => false/*,'expanded' => true, 'compound' =>true ) )*/
            ->add('name', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('file', 'file', array('label' => 'Фото','required'=>false))
            ->add('unit', null, array('label' => 'Марка'))
            ->add('price', null, array('label' => 'Цена'))
            ->add('current', null)
            ->add('voltage', null)
            ->add('power', null)
            ->add('dc_connector', null)
            ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('category', null, array('label' => 'Категория'))
            ->add('device', null, array('label' => 'Девайсы'))
            ->add('name', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('unit', null, array('label' => 'Марка'))
            ->add('price', null, array('label' => 'Цена'))
            ->add('current', null)
            ->add('voltage', null)
            ->add('power', null)
            ->add('dc_connector', null);
            }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Название'))
            ->add('category', null, array('label' => 'Категория'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('unit', null, array('label' => 'Марка'))
            ->add('price', null, array('label' => 'Цена'))
            ->add('current', null)
            ->add('voltage', null)
            ->add('power', null)
            ->add('dc_connector', null);
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
