<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\CatalogBundle\Command\SitemapCommand;

class CatalogController extends Controller
{
	// "custom router"
	public function exploreRouteAction($catUrl)
	{
		$sectionId = $this->getRequest()->query->get('section');
		$gbiId = $this->getRequest()->query->get('gbi');

		if (!empty($catUrl) && !empty($sectionId) && !empty($gbiId)) {
			return $this->forward('AppCatalogBundle:Catalog:exploreProduct', array(
				'catUrl' => $catUrl,
				'sectionId' => (int)$sectionId,
				'gbiId' => (int)$gbiId
			));
		} elseif (!empty($catUrl) || !empty($sectionId)) {
			return $this->forward('AppCatalogBundle:Catalog:exploreCategory', array(
				'catUrl' => $catUrl,
				'sectionId' => (int)$sectionId,
			));
		}
		die('Page is not found');
	}

	public function exploreCategoryAction($catUrl, $sectionId)
	{
		// search category ID

		$catId = array_search($catUrl, SitemapCommand::$baseCats);
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find(!empty($sectionId) ? $sectionId : $catId);
		if (empty($category)) {
			throw $this->createNotFoundException();
		}
		$parents = $catRp->getPath($category);
		if ($this->isParent($category, $catUrl)) {
			$hierarchyOptions = array(
				'childSort' => array(
					'field' => 'title',
					'dir' => 'asc'
				)
			);

			$children = $catRp->buildTreeObjects($catRp->getNodesHierarchy($category, false, $hierarchyOptions));

			if (!empty($children)) {
				return $this->render('AppCatalogBundle:Catalog:category.explore.html.twig', array(
					'parents' => $parents,
					'children' => $children,
					'catUrl' => SitemapCommand::$baseCats[$catId],
					'category' => $category
				));
			} else {
				$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
				$products = $prodRp->findBy(
					array('sectionId' => $sectionId),
					array('nomen' => 'ASC')
				);
				return $this->render('AppCatalogBundle:Catalog:section.explore.html.twig', array(
					'parents' => $parents,
					'catUrl' => SitemapCommand::$baseCats[$catId],
					'section' => $category,
					'products' => $products
				));
			}
		} else {
			//TODO:сделать редиректы
			return $this->render("AppMainBundle:StaticPage:404.html.twig");
		}
	}

	public function exploreProductAction($catUrl, $sectionId, $gbiId)
	{
		// search category ID
		$catId = array_search($catUrl, SitemapCommand::$baseCats);

		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$section = $catRp->find($sectionId);
		$parents = $catRp->getPath($section);
		$product = $prodRp->find($gbiId);

		return $this->render('AppCatalogBundle:Catalog:product.explore.html.twig', array(
			'parents' => $parents,
			'catUrl' => SitemapCommand::$baseCats[$catId],
			'section' => $section,
			'product' => $product
		));
	}

	/**
	 * Поискиовая выдача списка продуктов с пагинацей
	 * @return Response
	 */
	public function searchAction()
	{
		$condition = $this->getRequest()->query->get('condition');
		$page = $this->getRequest()->query->get('page', 1);

		if (empty($condition)) {
			return new Response();
		}

		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

		$products = $prodRp->search($condition, $page);
		foreach ($products as &$product) {
			$category = $product->getCategory();
			$path = $catRp->getPath($category);
			$product->catUrl = SitemapCommand::$baseCats[$path[0]->getId()];
			$product->section = $category;
			$product->path = $path;
		}

		return $this->render('AppCatalogBundle:Catalog:search.html.twig', array(
			'products' => $products
		));
	}

	/**
	 * Обрабатывает поисковые запросы с автокомплита
	 * @return Response
	 */
	public function suggestAction()
	{
		$term = $this->getRequest()->query->get('term');

		if (empty($term)) {
			return new Response();
		}

		$jsonSrv = new JsonEncoder();

		// возвращает массив данных для автокомплита
		$result = $this->get('catalog.autocomplete')->suggest($term);

		$json = $jsonSrv->encode($result, JsonEncoder::FORMAT);

		return new Response($json);
	}

	private function isParent($category, $catUrl)
	{

		$catRp = $this->getDoctrine()->getRepository("AppCatalogBundle:Category");
		$parents = $catRp->getPath($category);
		foreach ($parents as $parent) {
			if ($parent->getAlias() == $catUrl)
				return true;
		}
		return false;
	}
}