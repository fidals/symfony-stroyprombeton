<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StaticPageController extends Controller
{

	/**
	 * Эти урлы как первая часть путей до каталога. Когда-нить снесём
	 * Пока обрабатываем их хардкодом
	 * @var array
	 */
	public $baseCats = array(
		456 => 'prom-stroy',
		457 => 'dor-stroy',
		458 => 'ingener-stroy',
		459 => 'energy-stroy',
		460 => 'blag-territory',
		461 => 'neftegaz-stroy'
	);

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
			$product->catUrl = $this->baseCats[$path[0]->getId()];
		}

		return $this->render('AppMainBundle:StaticPage:indexPage.html.twig', array(
			'randomProducts' => $randomProducts
		));
	}
}