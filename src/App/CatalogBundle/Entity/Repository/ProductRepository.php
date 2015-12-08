<?php

namespace App\CatalogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ProductRepository
 * @package App\CatalogBundle\Entity\Repository
 */
class ProductRepository extends EntityRepository
{
	/**
	 * Константа - лимит позиций по умолчанию на одной странице
	 */
	const DEFAULT_LIMIT = 20;
	const UNCAT_PRODUCT_SEARCH_LIMIT = 100;

	/**
	 * Константа - максимальное количество последовательнных рандомных запросов случайных продуктов из базы.
	 * Нужна для предовращения бесконечного цикла while в методе getRandomProductsHasPhoto при отстутствии товаров с фотографиями.
	 */
	const MAX_RANDOM_GETS = 100;

	/**
	 * Сколько берем в запросе продуктов для поиска продуктов с картинками
	 */
	const DEFAULT_PER_QUERY = 100;

	/**
	 * Метод поиска для автодополнения
	 * Используется в TableGear
	 *
	 * @param $term
	 * @param int $limit
	 * @return array
	 */
	public function searchAutocomplete($term, $limit = self::DEFAULT_LIMIT)
	{
		return $this->getEntityManager()->getConnection()->query(
			'SELECT DISTINCT CONCAT(name, \' \', mark) as value FROM products
				WHERE section_id IS NOT NULL
					HAVING value LIKE (\'%' . $term . '%\') LIMIT 0, ' . $limit)->fetchAll();
	}

	/**
	 * Метод поиска
	 *
	 * @param $term
	 * @param int $page
	 * @param bool $returnObjAsArray
	 * @return array
	 */
	public function search($term, $page = 1, $perPage = self::DEFAULT_LIMIT, $returnObjAsArray = false)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$offset = ($page - 1) * $perPage;

		$expr = $qb
			->select('p')
			->from(self::getClassName(), 'p')
			->where($qb->expr()->concat('p.name', $qb->expr()->concat($qb->expr()->literal(' '), 'p.mark')) . ' LIKE :term')
			->setFirstResult($offset)
			->setMaxResults($perPage);

		$query = $expr
			->setParameter('term', '%' . $term . '%');

		return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult();
	}


	/**
	 * Ищет среди продуктов, которые без категории
	 * @param $term
	 * @param int $limit
	 * @param bool $returnObjAsArray
	 * @return array
	 */
	public function searchUncategorized($term, $limit = self::DEFAULT_LIMIT, $returnObjAsArray = false)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();

		$expr = $qb
			->select('p')
			->from(self::getClassName(), 'p')
			->where('p.sectionId is NULL')
			->andWhere($qb->expr()->concat('p.name', $qb->expr()->concat($qb->expr()->literal(' '), 'p.mark')) . ' LIKE :term')
			->setMaxResults($limit);

		$query = $expr
			->setParameter('term', '%' . $term . '%');
		return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult();
	}

	/**
	 * @param int $limit
	 * @return array
	 */
	public function getRandomProducts($limit = self::DEFAULT_LIMIT)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT p, RAND() AS HIDDEN rand FROM AppCatalogBundle:Product p WHERE p.isActive = 1 ORDER BY rand')
			->setMaxResults($limit)
			->getResult();
	}

	/**
	 * Метод поиска $limit-продуктов с фотографиями.
	 *
	 * Получаем DEFAULT_PER_QUERY товаров из базы и добавляем в результирующий массив те из них, что с фотографиями и еще не в массиве.
	 * Делаем это до тех пор, пока не наберем $limit в массиве или не превысим предел рандомных запросов (MAX_RANDOM_GETS), нужный для предотвращения бесконечного цикла.
	 * Если в результате работы метода получился массив меньше, чем $limit, мы кидаем Exception, иначе - возвращаем получивший массив.
	 *
	 * @param int $limit - нужное нам количество товаров с фотографиями
	 * @throws Exception - если размер получившегося массива меньше запрашиваемого
	 * @return array - limit рандомно-уникальных товаров с фотографиями
	 */
	public function getRandomProductsHasPhoto($limit = self::DEFAULT_LIMIT)
	{
		$productsWithPictures = array();
		$randomGets = 0;

		while(count($productsWithPictures) < $limit && $randomGets < self::MAX_RANDOM_GETS) {
			$randomProducts = $this->getRandomProducts(self::DEFAULT_PER_QUERY);
			$randomGets++;

			foreach ($randomProducts as $product) {
				if($product->hasPicture() && !in_array($product, $productsWithPictures) && count($productsWithPictures) < $limit) {
					$productsWithPictures[] = $product;
				}
			}
		}

		if (count($productsWithPictures) < $limit) {
			throw new Exception("Not enough products with pictures, check /assets/images/gbi-photos/ directory.");
		}

		return $productsWithPictures;
	}


	/**
	 * Возвращает массив свойств для TableGear
	 *
	 * @return array
	 */
	public function getTableGearProperties()
	{
		return [
			'h1'           => 'Заголовок',
			'name'         => 'Название',
			'title'        => 'Расширенный заголовок',
			'section_id'   => 'Категория',
			'description'  => 'Описание',
			'text'         => 'Текст',
			'introtext'    => 'Аннотация',
			'mark'         => 'Марка',
			'price'        => 'Цена',
            'price_date'   => 'Дата цены',
			'nomen'        => 'Код',
			'length'       => 'Длина (мм)',
			'width'        => 'Ширина (мм)',
			'height'       => 'Высота (мм)',
			'weight'       => 'Масса (кг)',
			'volume'       => 'Объем (м3)',
			'diameter_in'  => 'Внутренний диаметр (мм)',
			'diameter_out' => 'Внешний диаметр (мм)'
		];
	}

	public function getTableGearDefaultProperties()
	{
		return [
			'h1',
			'mark',
			'price',
			'nomen'
		];
	}
}
