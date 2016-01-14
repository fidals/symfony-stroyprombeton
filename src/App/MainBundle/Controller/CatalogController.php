<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\MainBundle\Command\SitemapCommand;

class CatalogController extends Controller
{
	public function categoryAction(Request $request)
	{
		$categoryId = $request->get('id');
		$catRp      = $this->getDoctrine()->getRepository('AppMainBundle:Category');
		$category   = $catRp->find($categoryId);

		if(!empty($category)) {
			$parents = $catRp->getPath($category);

			$hierarchyOptions = array(
				'childSort' => array(
					'field' => 'title',
					'dir'   => 'asc'
				)
			);

			$children = $catRp->buildTreeObjects($catRp->getNodesHierarchy($category, false, $hierarchyOptions));

			if (!empty($children)) {
				return $this->render('AppMainBundle:Catalog:category.explore.html.twig', array(
					'parents'  => $parents,
					'children' => $children,
					'category' => $category
				));
			} else {
				return $this->render('AppMainBundle:Catalog:section.explore.html.twig', array(
					'parents'  => $parents,
					'category' => $category
				));
			}
		} else {
			throw $this->createNotFoundException();
		}
	}

	public function categoriesFullAction()
	{
		return $this->render('AppMainBundle:Catalog:categories.html.twig');
	}

	public function productAction(Request $request)
	{
		$productId = $request->get('id');

		$catRp = $this->getDoctrine()->getRepository('AppMainBundle:Category');
		$prodRp = $this->getDoctrine()->getRepository('AppMainBundle:Product');

		$product = $prodRp->find($productId);

        if (empty($product)) {
            throw $this->createNotFoundException();
        }

		$category = $product->getCategory();
		$parents = $catRp->getPath($category);

		return $this->render('AppMainBundle:Catalog:product.explore.html.twig', array(
			'parents'  => $parents,
			'category' => $category,
			'product'  => $product
		));
	}

	// "custom router"
	public function exploreRouteAction($catUrl)
	{
		$sectionId = $this->getRequest()->query->get('section');
		$gbiId     = $this->getRequest()->query->get('gbi');

		if (!empty($catUrl) && !empty($sectionId) && !empty($gbiId)) {
			return $this->forward('AppMainBundle:Catalog:product', array(
				'id' => (int) $gbiId
			));
		} elseif (!empty($catUrl) || !empty($sectionId)) {
			return $this->forward('AppMainBundle:Catalog:category', array(
				'id' => (int) $sectionId,
			));
		}
	}

	/**
	 * Поискиовая выдача списка продуктов с пагинацей
	 * Поиск основан на данных из автокомплита
	 * @param Request $request
	 * @return Response
	 */
	public function searchResultsAction(Request $request)
	{
		$condition = $request->get('search');
		$page = $request->get('page', 1);
		$limit = 150;

		if (empty($condition)) {
			return new Response();
		}

		$searchResults = $this->get('catalog.search')->search($condition, $limit * $page);

		return $this->render('AppMainBundle:Search:results.search.html.twig', array(
			'elements' => $searchResults,
			'searchCondition' => $condition
		));

	}

	public function gbiVisualAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppMainBundle:Category');
		$baseCategories = $catRp->findById(array_keys(SitemapCommand::$baseCats));

		$hierarchyOptions = array(
			'childSort' => array(
				'field' => 'title',
				'dir'   => 'asc'
			)
		);
		$twigArgs = array();

		// Формируем массив вида ['ingener_stroy'] => < дети категории >
		foreach($baseCategories as &$category) {
			$argumentName = str_replace('-', '_', SitemapCommand::$baseCats[$category->getId()]);
			$twigArgs[$argumentName] = $catRp->buildTreeObjects($catRp->getNodesHierarchy($category, false, $hierarchyOptions));
		}

		return $this->render('AppMainBundle:Catalog:gbi.visual.html.twig', $twigArgs);
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

		/**
		 * Получаем результат поиска в виде ассоциативного массива с ключами categories и products.
		 * На основе него мы сделаем общий массив с нужными нам ключами для JSON ответа.
		 */
		$result = $this->get('catalog.search')->suggest($term);
		$router = $this->container->get('router');

		$categoryResults = array();
		foreach($result['categories'] as $category) {
			$categoryResults[] = array(
				'desc'   => $category->getDescription(),
				'label'  => $category->getName(),
				'razdel' => 1,
				'id' => $category->getId(),
				'url'    => $router->generate('app_catalog_category', array(
					'id' => $category->getId()
				)),
				'img' => $category->getPicturePath()
			);
		}

		$productResults = array();
		foreach ($result['products'] as $product) {
			$productResults[] = array(
				'desc' => $product->getDescription(),
				'label' => $product->getName(),
				'razdel' => 0,
				'url' => $router->generate(
					'app_catalog_product',
					array(
						'id' => $product->getId(),
					)
				),
				'id' => $product->getId(),
				'mark' => $product->getMark(),
				'price' => $product->getPrice(),
				'nomen' => $product->getNomen(),
				'img' => $product->getPicturePath()
			);
		}

		/**
		 * Получаем общий массив.
		 * Преобразовываем его в JSON и отдаем в виде респонса.
		 */
		$result = array_merge($categoryResults, $productResults);
		$json = $jsonSrv->encode($result, JsonEncoder::FORMAT);

		return new Response($json);
	}
}