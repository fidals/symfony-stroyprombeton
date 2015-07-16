<?php

namespace App\MainBundle\Controller;

use App\CatalogBundle\Command\SitemapCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Редиректит старые url от modx на новые
 * Class RedirectController
 * @package App\MainBundle\Controller
 */
class RedirectController extends Controller
{
	/**
	 * Редиректит объекты
	 * Сделанно именно так (а не через FrameworkBundle) потому что иначе в url появляется лишний параметр territoryId
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function appMainObjectAction(Request $request)
	{
		$router = $this->get('router');
		$url = $router->generate('app_main_object', array('id' => $request->get('id')));
		return new RedirectResponse($url, 301);
	}

	/**
	 * Редиректит url каталога с идентификатором в конце
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function appCatalogExploreAction(Request $request)
	{
		$lastId = $request->get('lastId');
		$category = $this->getDoctrine()->getRepository('AppCatalogBundle:Category')->find($lastId);
		$route = (!empty($category)) ? 'app_catalog_category' : 'app_catalog_product';
		$url = $this->get('router')->generate($route, array('id' => $lastId));
		return new RedirectResponse($url, 301);
	}

	/**
	 * Редиректит url с алиасом корневой категории в конце
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function appCatalogExploreTranslitAction(Request $request)
	{
		$categoryTranslit = $request->get('categoryTranslit');
		$categoryId = array_search($categoryTranslit, SitemapCommand::$baseCats);
		$url = $this->get('router')->generate('app_catalog_category', array('id' => $categoryId));
		return new RedirectResponse($url, 301);
	}
}
