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
	public function categoryAction(Request $request)
	{
		$categoryId = $request->get('id');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);
		if(!empty($category)) {
			$parents = $catRp->getPath($category);

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
					'category' => $category
				));
			} else {
				return $this->render('AppCatalogBundle:Catalog:section.explore.html.twig', array(
					'parents'  => $parents,
					'category' => $category
				));
			}
		} else {
			throw $this->createNotFoundException();
		}
	}

	public function productAction(Request $request)
	{
		$productId = $request->get('id');

		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');

		$product = $prodRp->find($productId);
		$category = $product->getCategory();
		$parents = $catRp->getPath($category);

		return $this->render('AppCatalogBundle:Catalog:product.explore.html.twig', array(
			'parents' => $parents,
			'category' => $category,
			'product' => $product
		));
	}

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

	public function gbiVisualAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$baseCategories = $catRp->findById(array_keys(SitemapCommand::$baseCats));

		$hierarchyOptions = array(
			'childSort' => array(
				'field' => 'title',
				'dir' => 'asc'
			)
		);
		$twigArgs = array();

		// Формируем массив вида ['ingener_stroy'] => < дети категории >
		foreach($baseCategories as &$category) {
			$argumentName = str_replace('-', '_', SitemapCommand::$baseCats[$category->getId()]);
			$twigArgs[$argumentName] = $catRp->buildTreeObjects($catRp->getNodesHierarchy($category, false, $hierarchyOptions));
		}

		return $this->render('AppCatalogBundle:Catalog:gbi.visual.html.twig', $twigArgs);
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
}