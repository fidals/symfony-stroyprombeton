<?php
namespace App\CatalogBundle\Admin;

use App\CatalogBundle\Controller\CatalogController;
use App\CatalogBundle\Entity\Repository\ProductRepository;
use EntityManager51ef8ed0363a3_546a8d27f194334ee012bfe64f629947b07e4919\__CG__\Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'название'))
            ->add('mark', null, array('label' => 'марка'))
            ->add('nomen', null, array('label'=>'номенклатурный номер', 'required' => false))
            ->add('category', 'sonata_type_model', array('label' => 'Родитель'))
            ->add('isActive', null, array('label' => 'используется'))
            ->add('newPrice', null, array('label' => 'акутуальная цена'))
            ->add('mark', null, array('required' => true))

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'название'))
            ->add('mark', null, array('label' => 'марка'))
            ->add('nomen', null, array('label'=>'номенклатурный номер'))
            ->add('isActive', null, array('label' => 'используется'))
            ->add('newPrice', null, array('label' => 'акутуальная цена'))
            ->add('category', null, array('label' => 'Родитель'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('nomen', null, array('label'=>'номенклатурный номер'))
            ->addIdentifier('name', null, array('label' => 'название'))
            ->add('mark', null, array('label' => 'марка'))
            ->add('isActive', null, array('label' => 'используется'))
            ->add('newPrice', null, array('label' => 'акутуальная цена'))
	        ->add('category', null, array('label' => 'Родитель'))

        ;
    }

}