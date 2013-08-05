<?php

namespace App\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Настройки SonataAdmin для StaticPage
 *
 * Class StaticPageAdmin
 * @package App\MainBundle\Admin
 */
class StaticPageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('alias', null, array('label' => 'Прямая ссылка'))
            ->add('published', null, array('label' => 'Доступно'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Содержимое'));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper

            ->add('title', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('alias', null, array('label' => 'Прямая ссылка'))
            ->add('published', null, array('label' => 'Доступно'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Содержимое'));
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title', null, array('label' => 'Название'))
            ->add('description', null, array('label' => 'Описание'))
            ->add('alias', null, array('label' => 'Прямая ссылка'))
            ->add('published', null, array('label' => 'Доступно'))
            ->add('introtext', null, array('label' => 'Вводный текст'))
            ->add('content', null, array('label' => 'Содержимое'));
        ;
    }
}
