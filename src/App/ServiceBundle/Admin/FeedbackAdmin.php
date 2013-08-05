<?php

namespace App\ServiceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для Feedback
 *
 * Class FeedbackAdmin
 * @package App\ServiceBundle\Admin
 */
class FeedbackAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('name', null, array('label' => 'Имя'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('email', null, array('label' => 'Email'))
            ->add('date', null, array('label' => 'Время'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('label' => 'Имя'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('email', null, array('label' => 'Email'))
            ->add('date', null, array('label' => 'Время'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name', null, array('label' => 'Имя'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('email', null, array('label' => 'Email'))
            ->add('date', null, array('label' => 'Время'));
    }
}
