<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\CategoryType;
use App\MainBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryAdminController.
 */
class CategoryAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:Category';
    const ENTITY_NAME = 'category';
    const ENTITY_FORM = CategoryType::class;
    const PARENT_ENTITY = 'AppMainBundle:Category';

    const LIST_TEMPLATE = 'AppAdminBundle:Category:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:Category:new.html.twig';

    /**
     * @var array массив возможных фильтров, применимых к сущности
     */
    protected $filters = array('id', 'name', 'parent', 'mark', 'isActive');

    /**
     * @Route("/category/list/{page}", defaults={"page" = 1}, name="admin_category_list")
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
     * @Route("/category/edit/{id}", defaults={"id" = 0}, name="admin_category_edit")
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
     * @Route("/category/delete/{id}", name="admin_category_delete")
     * @ParamConverter("category", class="AppMainBundle:Category")
     *
     * @param Category $category
     *
     * @return RedirectResponse
     */
    public function deleteAction(Category $category)
    {
        $this->deleteEntity($category);

        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @return Category
     */
    protected function createEntity()
    {
        return new Category();
    }
}
