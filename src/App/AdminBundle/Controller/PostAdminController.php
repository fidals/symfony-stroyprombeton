<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\PostType;
use App\MainBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostAdminController.
 *
 * Контроллер для Новостей (Post).
 */
class PostAdminController extends AbstractEntityController
{
    const ENTITY = 'AppMainBundle:Post';
    const ENTITY_NAME = 'post';
    const ENTITY_FORM = PostType::class;

    const LIST_TEMPLATE = 'AppAdminBundle:Post:list.html.twig';
    const EDIT_TEMPLATE = 'AppAdminBundle:Post:new.html.twig';

    /**
     * @var array массив возможных фильтров, применимых к сущности
     */
    protected $filters = array('id', 'name', 'isActive');

    /**
     * @Route("/post/list/{page}", defaults={"page" = 1}, name="admin_post_list")
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
     * @Route("/post/edit/{id}", defaults={"id" = 0}, name="admin_post_edit")
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
     * @Route("/post/delete/{id}", name="admin_post_delete")
     * @ParamConverter("post", class="AppMainBundle:Post")
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Post $post)
    {
        $this->deleteEntity($post);

        return $this->redirectToRoute('admin_post_list');
    }

    /**
     * @return Post
     */
    protected function createEntity()
    {
        return new Post();
    }
}
