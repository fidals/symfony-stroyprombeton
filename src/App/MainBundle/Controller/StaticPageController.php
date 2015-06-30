<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\CatalogBundle\Command\SitemapCommand;

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
		return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', $twigArgs);
	}

	/**
	 * Главная страница
	 * @return Response
	 */
	public function showIndexAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

		$randomProducts = $this->getDoctrine()
			->getRepository('AppCatalogBundle:Product')
			->getRandomProducts();

		foreach ($randomProducts as &$product) {
			$path = $catRp->getPath($product->getCategory());
			$product->catUrl = SitemapCommand::$baseCats[$path[0]->getId()];
		}

		return $this->render('AppMainBundle:StaticPage:indexPage.html.twig', array(
			'randomProducts' => $randomProducts
		));
	}

	public function exeptionAction()
	{
		return $this->render('AppMainBundle:StaticPage:404.html.twig');
	}

}