<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\MainBundle\Command\SitemapCommand;

class StaticPageController extends Controller
{

    /**
     * Показывает статичные страницы напрямую из базы
     * @param $alias - по факту полный урл. Т.е. может содержать символ "/"
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($alias)
    {
        // prepare alias
        $alias = trim($alias, ' /');

        // search in repository
        $spRepository = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $staticPage = $spRepository->findOneByAlias($alias);

        if (empty($staticPage)) {
            throw $this->createNotFoundException();
        }

        $twigArgs = array('staticPage' => $staticPage);
        return $this->render('AppMainBundle:StaticPage:static.page.html.twig', $twigArgs);
    }

    /**
     * Главная страница
     * @return Response
     */
    public function showIndexAction()
    {
        $catRp = $this->getDoctrine()->getRepository('AppMainBundle:Category');

        $mainCategoryIds = array(12, 15, 473, 180, 12563, 176, 402, 44, 59);
        $isNewMainCategoryIds = array(473, 12563);
        $mainCategories = $catRp->findById($mainCategoryIds);
        $mainCategoriesById = array();
        foreach ($mainCategories as $mainCategory) {
            $mainCategory->catUrl = SitemapCommand::$baseCats[$catRp->getPath($mainCategory)[0]->getId()];
            $mainCategory->isNew = in_array($mainCategory->getId(), $isNewMainCategoryIds);
            $mainCategoriesById[$mainCategory->getId()] = $mainCategory;
        }

        $mainCategoriesSorted = array();
        foreach ($mainCategoryIds as $mainCategoryId) {
            $mainCategoriesSorted[] = $mainCategoriesById[$mainCategoryId];
        }

        $productsWithPictures = $this->getDoctrine()->getRepository('AppMainBundle:Product')->getRandomProductsHasPhoto(10);

        foreach ($productsWithPictures as &$product) {
            $path = $catRp->getPath($product->getCategory());
            $product->catUrl = SitemapCommand::$baseCats[$path[0]->getId()];
        }

        // берем три последние новости
        $newsRp = $this->getDoctrine()->getRepository('AppMainBundle:Post');
        $lastNews = $newsRp->findBy(array('isActive' => 1), array('date' => 'DESC'), 3);

        return $this->render('AppMainBundle:StaticPage:index.page.html.twig', array(
            'products'       => $productsWithPictures,
            'news'           => $lastNews,
            'mainCategories' => $mainCategoriesSorted
        ));
    }

    public function exeptionAction()
    {
        return $this->render('AppMainBundle:StaticPage:404.html.twig');
    }
}
