<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\ProductType;
use App\MainBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductAdminController.
 */
class ProductAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:Product';
    const ENTITY_NAME = 'product';
    const ENTITY_FORM = ProductType::class;

    const PARENT_ENTITY = 'AppMainBundle:Category';
    const LIST_TEMPLATE = 'AppAdminBundle:Product:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:Product:new.html.twig';

    /**
     * @var array массив возможных фильтров, применимых к сущности
     */
    protected $filters = array('id', 'nomen', 'name', 'category', 'mark', 'price', 'isActive');

    /**
     * @Route("/product/list/{page}", defaults={"page" = 1}, name="admin_product_list")
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
     * @Route("/product/edit/{id}", defaults={"id" = 0}, name="admin_product_edit")
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
     * @Route("/product/delete/{id}", name="admin_product_delete")
     * @ParamConverter("product", class="AppMainBundle:Product")
     *
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $this->deleteEntity($product);

        return $this->redirectToRoute('admin_product_list');
    }

    /**
     * @return Product
     */
    protected function createEntity()
    {
        return new Product();
    }
}
