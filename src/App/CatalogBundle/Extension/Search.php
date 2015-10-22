<?php

namespace App\CatalogBundle\Extension;

use App\CatalogBundle\Command\SitemapCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * Поиск для главной
 * Class Search
 * @package App\CatalogBundle\Extension
 */
class Search
{
	/**
	 * Контейнер
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	public $container;

	/**
	 * Лимит элементов в поиске
	 * Если найдено категорий n >= LIMIT, то продукты не ищем, если n < LIMIT то ишем LIMIT - n продуктов
	 */
	const SEARCH_DEFAULT_LIMIT = 50;
	const SUGGEST_DEFAULT_LIMIT = 20;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Метод поиска
	 * Использует автокомплит
	 * @param $term
	 * @param $limit
	 * @return mixed
	 */
	public function search($term, $limit = self::SEARCH_DEFAULT_LIMIT)
	{
		return $this->suggest($term, $limit);
	}

	/**
	 * Автокомплит
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	public function suggest($term, $limit = self::SUGGEST_DEFAULT_LIMIT)
	{
		$categoryResults = $this->suggestCategories($term, $limit);
		$categoryResultsCount = count($categoryResults);
		$productResults = array();
		if($categoryResultsCount != $limit) {
			$productResults = $this->suggestProducts($term, $limit - $categoryResultsCount);
		}
		// отдаем ранжированный массив, сначала категории, потом продукты
		return array_merge($categoryResults, $productResults);
	}

	/**
	 * Автокомплит для категорий
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	private function suggestCategories($term, $limit = self::SEARCH_DEFAULT_LIMIT)
	{
		// инициализация rsm
		// rsm нужен для того чтобы получить массив сущностей из обычного sql запроса
		$categoryRsm = new ResultSetMapping();
		$categoryRsm->addEntityResult('AppCatalogBundle:Category', 'c');
		$categoryRsm->addFieldResult('c', 'id', 'id');
		$categoryRsm->addFieldResult('c', 'name', 'name');
		$categoryRsm->addFieldResult('c', 'description', 'description');
		$categoryRsm->addFieldResult('c', 'parentId', 'parent_id');

		// native query запрос для категорий
		$categoryNativeQuery = "
			SELECT id, description, name, parent_id,
				CASE WHEN name = :term THEN 0
					WHEN name LIKE :term_ THEN 1
					WHEN name LIKE :_term_ THEN 2
					WHEN name LIKE :_term THEN 3
					ELSE 4
				END AS priority
			FROM categories
			WHERE is_active = 1
				AND (name LIKE :_term_ OR title LIKE :_term_)
			ORDER BY priority, name ASC LIMIT 0, :limit";

		$categoryQuery = $this->container
			->get('doctrine')
			->getManager()
			->createNativeQuery($categoryNativeQuery, $categoryRsm)
			->setParameters(
				array(
					'term' => $term,
					'_term_' => '%' . $term . '%',
					'term_' => $term . '%',
					'_term' => '%' . $term,
					'limit' => $limit
				)
			);

		$categories = $categoryQuery->getResult();

		/**
		 * Получаем router для генерации урлов. Нужно для работающего автокомплита.
		 */
		$router = $this->container->get('router');

		$categoryResults = array();
		foreach($categories as $category) {
			$categoryResults[] = array(
				'desc'   => $category->getDescription(),
				'label'  => $category->getName(),
				'razdel' => 1,
				'id'     => $category->getId(),
				'url'    => $router->generate('app_catalog_category', array(
					'id' => $category->getId()
				)),
				'img' => $category->getPicturePath()
			);
		}
		return $categoryResults;
	}

	/**
	 * Автокомплит для продуктов
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	private function suggestProducts($term, $limit = self::SUGGEST_DEFAULT_LIMIT)
	{
		// инициализация rsm
		// rsm нужен для того чтобы получить массив сущностей из обычного sql запроса
		$productRsm = new ResultSetMapping();
		$productRsm->addEntityResult('AppCatalogBundle:Product', 'p');
		$productRsm->addFieldResult('p', 'id', 'id');
		$productRsm->addFieldResult('p', 'name', 'name');
		$productRsm->addFieldResult('p', 'description', 'description');
		$productRsm->addFieldResult('p', 'mark', 'mark');
		$productRsm->addFieldResult('p', 'price', 'price');
		$productRsm->addFieldResult('p', 'nomen', 'nomen');

		// native query запрос для продуктов
		$productsNativeQuery = "
			SELECT id, description, name, mark, price, nomen,
				CASE WHEN name = :term THEN 0
					WHEN name LIKE :term_ THEN 1
					WHEN CONCAT(introtext, ' ', name) LIKE :_term_ THEN 2
					WHEN name LIKE :_term THEN 3
					ELSE 4
				END AS priority
			FROM products
			WHERE is_active = 1
				AND (name LIKE :_term_ OR title LIKE :_term_ OR introtext LIKE :_term_)
			ORDER BY priority, name ASC LIMIT 0, :limit";

		$categoryQuery = $this->container
			->get('doctrine')
			->getManager()
			->createNativeQuery($productsNativeQuery, $productRsm)
			->setParameters(
				array(
					'term' => $term,
					'_term_' => '%' . $term . '%',
					'term_' => $term . '%',
					'_term' => '%' . $term,
					'limit' => $limit
				)
			);

		$products = $categoryQuery->getResult();

		/**
		 * Получаем router для генерации урлов. Нужно для работающего автокомплита.
		 */
		$router = $this->container->get('router');

		$productResults = array();
		foreach ($products as $product) {
			$productResults[] = array(
				'desc'   => $product->getDescription(),
				'label'  => $product->getName(),
				'razdel' => 0,
				'url' => $router->generate('app_catalog_product', array(
					'id' => $product->getId(),
				)),
				'id' => $product->getId(),
				'mark'  => $product->getMark(),
				'price' => $product->getPrice(),
				'nomen' => $product->getNomen(),
				'img' => $product->getPicturePath()
			);
		}

		return $productResults;
	}
}