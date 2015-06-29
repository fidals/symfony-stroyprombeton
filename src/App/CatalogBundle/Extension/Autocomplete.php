<?php

namespace App\CatalogBundle\Extension;

use App\CatalogBundle\Command\SitemapCommand;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Автокомплит для главной
 * Class Autocomplete
 * @package App\CatalogBundle\Extension
 */
class Autocomplete
{
	/**
	 * Контейнер
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	public $container;

	/**
	 * Лимит элементов в автокомплите
	 * Если найдено категорий n >= LIMIT, то продукты не ищем, если n < LIMIT то ишем LIMIT - n продуктов
	 */
	const DEFAULT_LIMIT = 50;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}


	/**
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	public function suggest($term, $limit = self::DEFAULT_LIMIT)
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
	private function suggestCategories($term, $limit = self::DEFAULT_LIMIT)
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

		// генерация url категорий
		$router = $this->container->get('router');
		$catRp = $this->container->get('doctrine')->getRepository('AppCatalogBundle:Category');

		$categoryResults = array();
		foreach($categories as $category) {
			$path = $catRp->getPath($category);
			$catUrl = SitemapCommand::$baseCats[$path[0]->getId()];

			$categoryResults[] = array(
				'desc'   => $category->getDescription(),
				'label'  => $category->getName(),
				'razdel' => 1,
				'url'    => $router->generate('app_catalog_explore_category', array(
					'catUrl' => $catUrl,
					'section' => $category->getId()
				))
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
	private function suggestProducts($term, $limit = self::DEFAULT_LIMIT)
	{
		// sql запрос для продуктов
		$productsQuery = "
			SELECT id, description, name, section_id,
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

		$stmt = $this->container->get('doctrine')->getConnection()->prepare($productsQuery);
		$stmt->bindValue('term', $term);
		$stmt->bindValue('_term_', '%' . $term . '%');
		$stmt->bindValue('term_',  $term . '%');
		$stmt->bindValue('_term', '%' . $term);
		$stmt->bindValue('limit', $limit, \PDO::PARAM_INT);


		$stmt->execute();
		$products = $stmt->fetchAll();

		// генерация url продуктов
		$router = $this->container->get('router');
		$catRp = $this->container->get('doctrine')->getRepository('AppCatalogBundle:Category');

		$productResults = array();
		foreach($products as $product) {
			$category = $catRp->find($product['section_id']);
			$path = $catRp->getPath($category);
			$catUrl = SitemapCommand::$baseCats[$path[0]->getId()];

			$productResults[] = array(
				'desc' => $product['description'],
				'label' => $product['name'],
				'razdel' => 0,
				'url' => $router->generate('app_catalog_explore_category', array(
					'catUrl'  => $catUrl,
					'section' => $category->getId(),
					'gbi'     => $product['id']
				))
			);
		}
		return $productResults;
	}
}