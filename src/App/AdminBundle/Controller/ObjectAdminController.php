<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\ObjectType;
use App\MainBundle\Entity\Object;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ObjectAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:Object';
    const ENTITY_NAME = 'object';
    const ENTITY_FORM = ObjectType::class;
    const PARENT_ENTITY = 'AppMainBundle:Territory';

    const LIST_TEMPLATE = 'AppAdminBundle:Object:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:Object:new.html.twig';

    /**
     * @var array массив возможных фильтров, применимых к сущности
     */
    protected $filters = array('id', 'name', 'territory', 'isActive');

    /**
     * @Route("/object/list/{page}", defaults={"page" = 1}, name="admin_object_list")
     *
     * @param int     $page
     * @param Request $request
     *
     * @return Response
     */
    public function listAction($page, Request $request)
    {
        return $this->renderListTemplate($page, $request);
    }

    /**
     * @Route("/object/edit/{id}", defaults={"id" = 0}, name="admin_object_edit")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        return $this->renderEditEntityTemplate($request, $id);
    }

    /**
     * @Route("/object/delete/{id}", name="admin_object_delete")
     * @ParamConverter("object", class="AppMainBundle:Object")
     *
     * @param object $object
     *
     * @return RedirectResponse
     */
    public function deleteAction(Object $object)
    {
        $this->deleteEntity($object);

        return $this->redirectToRoute('admin_object_list');
    }

    /**
     * @return object
     */
    protected function createEntity()
    {
        return new Object();
    }
}
