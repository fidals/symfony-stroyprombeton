<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\TerritoryType;
use App\MainBundle\Entity\Territory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class TerritoryAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:Territory';
    const ENTITY_NAME = 'territory';
    const ENTITY_FORM = TerritoryType::class;

    const LIST_TEMPLATE = 'AppAdminBundle:Territory:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:Territory:new.html.twig';

    /**
     * @var array массив возможных фильтров, применимых к сущности
     */
    protected $filters = array('id', 'name', 'translitName', 'isActive');

    /**
     * @Route("/territory/list/{page}", defaults={"page" = 1}, name="admin_territory_list")
     *
     * @param int     $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($page, Request $request)
    {
        return $this->renderListTemplate($page, $request);
    }

    /**
     * @Route("/territory/edit/{id}", defaults={"id" = 0}, name="admin_territory_edit")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        return $this->renderEditEntityTemplate($request, $id);
    }

    /**
     * @Route("/territory/delete/{id}", name="admin_territory_delete")
     * @ParamConverter("territory", class="AppMainBundle:Territory")
     *
     * @param Territory $territory
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Territory $territory)
    {
        $this->deleteEntity($territory);

        return $this->redirectToRoute('admin_territory_list');
    }

    /**
     * @return Territory
     */
    protected function createEntity()
    {
        return new Territory();
    }
}
