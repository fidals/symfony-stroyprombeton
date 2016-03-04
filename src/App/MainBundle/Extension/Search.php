<?php

namespace App\MainBundle\Extension;

use App\MainBundle\Command\SitemapCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * Поиск для главной
 * Class Search
 * @package App\MainBundle\Extension
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
	 * Делегирует вызов методу автокомплита
	 * @param $term
	 * @param $limit
	 * @return mixed
	 */
	public function search($term, $limit = self::SEARCH_DEFAULT_LIMIT)
	{
		return $this->suggest($term, $limit);
	}

	/**
	 * Общий метод для автокомплита и поиска.
	 * Получаем результаты вызовов аналогичных методов для Категорий и Товаров, после чего возвращаем массив с соотв. ключами.
	 * @param mixed $term - условие поиска
	 * @param int $limit - нужное количество "подсказок" для автокомплита
	 * @return array - ассоциативный массив с результатами поиска по категориям и продуктам под соотв. ключами
	 */
	public function suggest($term, $limit = self::SUGGEST_DEFAULT_LIMIT)
	{
		$categorySuggest = $this->suggestCategories($term, $limit);
		$categoryResultsCount = count($categorySuggest);
		$productSuggest = array();
		if($categoryResultsCount != $limit) {
			$productSuggest = $this->suggestProducts($term, $limit - $categoryResultsCount);
		}

		return array(
			'categories' => $categorySuggest,
			'products' => $productSuggest
		);
	}

	/**
	 * Автокомплит для категорий
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	private function suggestCategories($term, $limit = self::SUGGEST_DEFAULT_LIMIT)
	{
		// инициализация rsm
		// rsm нужен для того чтобы получить массив сущностей из обычного sql запроса
		$categoryRsm = new ResultSetMapping();
		$categoryRsm->addEntityResult('AppMainBundle:Category', 'c');
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

		return $categories;
	}

	/**
	 * Автокомплит для продуктов
	 * @param $term
	 * @param int $limit
	 * @return array - массив сущностей, полученный из SQL-запроса через RSM
	 */
	private function suggestProducts($term, $limit = self::SUGGEST_DEFAULT_LIMIT)
	{
		// инициализация rsm
		// rsm нужен для того чтобы получить массив сущностей из обычного sql запроса
		$productRsm = new ResultSetMapping();
		$productRsm->addEntityResult('AppMainBundle:Product', 'p');
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

		return $products;
	}
}