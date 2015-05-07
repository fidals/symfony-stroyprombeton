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
	 * Отображает статичную страницу с алиасом 'obekty', наполняет ее объектами из базы
	 * @return Response
	 */
	public function showObjectsAction()
	{
		$spRepository = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
		$goRp = $this->getDoctrine()->getRepository('AppMainBundle:GbiObject');

		$staticPage = $spRepository->findOneByAlias('obekty');
		$objects = $goRp->findAll();

		$twigArgs = array();
		$twigArgs['staticPage'] = $staticPage;
		$twigArgs['objects'] = $objects;

		return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', $twigArgs);
	}

	/**
	 * Показывает статичные страницы напрямую из базы
	 * @param $alias - по факту полный урл. Т.е. может содержать символ '/'
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

		return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', array('staticPage' => $staticPage));
	}

	/**
	 * Главная страница
	 * @return Response
	 */
	public function showIndexAction()
	{
		return $this->render('AppMainBundle:StaticPage:indexPage.html.twig');
	}

	/**
	 * Страница 'Наши объекты'
	 * @param $alias
	 * @return Response
	 */
	public function gbiObjeсtShowAction($alias)
	{
		$repo = $this->getDoctrine()->getRepository('AppMainBundle:GbiObject');
		if ($gbi_object = $repo->findOneByAlias($alias)) {
			return $this->render('AppMainBundle:StaticPage:gbiObject.html.twig', array(
				'gbiObject' => $gbi_object
			));
		}
		return $this->render('AppMainBundle:StaticPage:404.html.twig');
	}
}