<?php
namespace App\CatalogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nomen', null, array('required' => false))
            ->add('parent', 'sonata_type_model', array('label' => 'parent_id','required' => false))
            ->add('name', null, array('label' => 'Название','required' => true))
            ->add('title', null, array('required' => false))
            ->add('alias', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->add('order', null, array('label' => 'ord','required' => false))
            ->add('coefficient',null, array('required' => true,'data'=>'1.1'))
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('description', null, array('required' => false))
            ->add('file', 'file', array('label' => 'фото','required'=>false))

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('nomen', null, array('required' => false))
            ->add('parent', null, array('label' => 'parent_id','required' => false))
            ->add('name', null, array('label' => 'Название','required' => true))
            ->add('title', null, array('required' => false))
            ->add('alias', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->add('order', null, array('label' => 'ord','required' => false))
            ->add('coefficient',null, array('required' => true))
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('description', null, array('required' => false))

        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('nomen', null, array('required' => false))
            ->add('parent', null, array('label' => 'parent_id','required' => false))
            ->addIdentifier('name', null, array('label' => 'Название','required' => true))
            ->add('title', null, array('required' => false))
            ->add('alias', null, array('required' => false))
            ->add('mark', null, array('required' => false))
            ->add('order', null, array('label' => 'ord','required' => false))
            ->add('coefficient',null, array('required' => true))
            ->add('isActive', null, array('label' => 'is_active','required' => false))
            ->add('description', null, array('required' => false))

        ;
    }
    public function postPersist($category) {

        $this->saveFile($category);
    }

    public function preUpdate($category) {
        $this->saveFile($category);
    }

    public function saveFile($category) {
        $basepath = $this->getRequest()->getBasePath();
        $category->upload($basepath);
    }
    public function preRemove($category){
        $category->rmUploaded();
    }
}