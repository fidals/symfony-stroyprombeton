<?php

namespace App\CatalogBundle\Extension;

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
	const LIMIT = 50;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $term
	 * @param $baseCats
	 * @return array
	 */
	public function suggest($term, $baseCats)
	{
		// native query запрос для категорий
		$categoryRsm = new ResultSetMapping();
		$categoryRsm->addEntityResult('AppCatalogBundle:Category', 'c');
		$categoryRsm->addFieldResult('c', 'id', 'id');
		$categoryRsm->addFieldResult('c', 'name', 'name');
		$categoryRsm->addFieldResult('c', 'description', 'description');
		$categoryRsm->addFieldResult('c', 'parentId', 'parent_id');

		$categoryNativeQuery = "SELECT id, description, name
			FROM categories
			WHERE is_active = 1
			AND (name LIKE :_term_
			OR title LIKE :_term_)
			ORDER BY CASE WHEN name = :term THEN 0
						  WHEN name LIKE :term_ THEN 1
						  WHEN name LIKE :_term_ THEN 2
						  WHEN name LIKE :_term THEN 3
						  ELSE 4
					 END,
					 name ASC LIMIT 0, :limit";

		$categoryQuery = $this->container
			->get('doctrine')
			->getManager()
			->createNativeQuery($categoryNativeQuery, $categoryRsm)
			->setParameters(
				array(
					'tbl' => 'categories',
					'term' => $term,
					'_term_' => '%' . $term . '%',
					'term_' => $term . '%',
					'_term' => '%' . $term,
					'limit' => self::LIMIT
				)
			);

		$categories = $categoryQuery->getResult();

		$products = array();
		if(count($categories) !== self::LIMIT) {
			// native query запрос для продуктов
			$productRsm = new ResultSetMapping();
			$productRsm->addEntityResult('AppCatalogBundle:Product', 'p');
			$productRsm->addFieldResult('p', 'id', 'id');
			$productRsm->addFieldResult('p', 'name', 'name');
			$productRsm->addFieldResult('p', 'description', 'description');
			$productRsm->addFieldResult('p', 'section_id', 'sectionId');

			$productsNativeQuery = "SELECT id, description, name, section_id
				FROM products
				WHERE is_active = 1
				AND (name LIKE :_term_
				OR title LIKE :_term_
				OR introtext LIKE :_term_)
				ORDER BY CASE WHEN name = :term THEN 0
							  WHEN name LIKE :term_ THEN 1
							  WHEN CONCAT(introtext, ' ', name) LIKE :_term_ THEN 2
							  WHEN name LIKE :_term THEN 3
							  ELSE 4
						 END,
						 name ASC LIMIT 0, :limit";

			$productsQuery= $this->container
				->get('doctrine')
				->getManager()
				->createNativeQuery($productsNativeQuery, $productRsm)
				->setParameters(
					array(
						'term' => $term,
						'_term_' => '%' . $term . '%',
						'term_' => $term . '%',
						'_term' => '%' . $term,
						'limit' => self::LIMIT - count($categories)
					)
				);

			$products = $productsQuery->getResult();
		}

		// генерация url категорий
		$router = $this->container->get('router');
		$catRp = $this->container->get('doctrine')->getRepository('AppCatalogBundle:Category');

		$categoryResults = array();
		foreach($categories as $category) {
			$path = $catRp->getPath($category);
			$catUrl = $baseCats[$path[0]->getId()];

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

		// генерация url продуктов
		$productResults = array();
		foreach($products as $product) {
			$category = $catRp->find($product->getSectionId());
			$path = $catRp->getPath($category);
			$catUrl = $baseCats[$path[0]->getId()];

			$productResults[] = array(
				'desc' => $product->getDescription(),
				'label' => $product->getName(),
				'razdel' => 0,
				'url' => $router->generate('app_catalog_explore_category', array(
					'catUrl'  => $catUrl,
					'section' => $category->getId(),
					'gbi'     => $product->getId()
				))
			);
		}

		// отдаем ранжированный массив, сначала категории, потом продукты
		return array_merge($categoryResults, $productResults);;
	}
}