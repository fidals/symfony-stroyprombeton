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
		537 => 'prom-stroy',
		538 => 'dor-stroy',
		539 => 'ingener-stroy',
		540 => 'energy-stroy',
		541 => 'blag-territory',
		542 => 'neftegaz-stroy'
	);

	/**
	 * Показывает статичные страницы напрямую из базы
	 * @param $alias - по факту полный урл. Т.е. может содержать символ "/"
	 * @return Response
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

	public function showObjectsAction()
	{
		$spRepository = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
		$staticPage = $spRepository->findOneByAlias('obekty');
		$goRp = $this->getDoctrine()->getRepository("AppMainBundle:GbiObject");
		$objects = $goRp->findAll();
		$twigArgs = [
			'staticPage' => $staticPage,
			'objects'    => $objects
		];
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

	/**
	 * Страница "Наши объекты"
	 * @param $alias
	 * @return Response
	 */
	public function gbiObjeсtShowAction($alias)
	{
		$repo = $this->getDoctrine()->getRepository("AppMainBundle:GbiObject");
		if ($gbi_object = $repo->findOneByAlias($alias)) {
			return $this->render("AppMainBundle:StaticPage:gbiObject.html.twig", array(
				'gbiObject' => $gbi_object
			));
		}
		return $this->render('AppMainBundle:StaticPage:404.html.twig');
	}

	public function exeptionAction()
	{
		return $this->render('AppMainBundle:StaticPage:404.html.twig');
	}

}