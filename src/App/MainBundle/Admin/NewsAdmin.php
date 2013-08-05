<?php

namespace App\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для StaticPage
 *
 * Class NewsAdmin
 * @package App\MainBundle\Admin
 */
class NewsAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('header', null, array('label' => 'Заголовок'))
            ->add('alias', null, array('label' => 'Alias'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Текст'))
            ->add('date', null, array('label' => 'Дата публикации'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('header', null, array('label' => 'Заголовок'))
            ->add('alias', null, array('label' => 'Alias'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Текст'))
            ->add('date', null, array('label' => 'Дата публикации'));
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('header', null, array('label' => 'Заголовок'))
            ->add('alias', null, array('label' => 'Alias'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Текст'))
            ->add('date', null, array('label' => 'Дата публикации'));
        ;
    }
}
