<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\StaticPageType;
use App\MainBundle\Entity\StaticPage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class StaticPageAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:StaticPage';
    const ENTITY_NAME = 'staticpage';
    const ENTITY_FORM = StaticPageType::class;

    const LIST_TEMPLATE = 'AppAdminBundle:StaticPage:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:StaticPage:new.html.twig';

    protected $filters = array('id', 'name', 'alias', 'isActive');

    /**
     * @Route("/staticpage/list/{page}", defaults={"page" = 1}, name="admin_staticpage_list")
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
     * @Route("/staticpage/edit/{id}", defaults={"id" = 0}, name="admin_staticpage_edit")
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
     * @Route("/staticpage/delete/{id}", name="admin_staticpage_delete")
     * @ParamConverter("staticPage", class="AppMainBundle:StaticPage")
     *
     * @param StaticPage $staticPage
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(StaticPage $staticPage)
    {
        $this->deleteEntity($staticPage);

        return $this->redirectToRoute('admin_staticpage_list');
    }

    /**
     * @return StaticPage
     */
    protected function createEntity()
    {
        return new StaticPage();
    }
}
