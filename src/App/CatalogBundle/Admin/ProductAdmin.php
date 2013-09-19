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
            ->add('name', null, array('required' => true))
            ->add('category', 'sonata_type_model', array('label' => 'Родитель'))
            ->add('isActive', null)
            ->add('newPrice', null)
            ->add('mark', null, array('required' => true))

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('required' => true))

            ->add('category', null, array('label' => 'Родитель'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name', null, array('required' => false))

	        ->add('category', null, array('label' => 'Родитель'))
//            ->add('category', null, array('required' => false))
        ;
    }

}