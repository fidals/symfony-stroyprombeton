<?php

namespace App\CatalogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

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
	 * Выбираем N продуктов из базы, не глядя на картинки, а потом берём из этой сотни первые $limit с картинками.
	 * @param int $limit
	 * @return array
	 */
	public function getRandomProductsHasPhoto($limit = self::DEFAULT_LIMIT)
	{
		$productsWithPictures = array();
		$productsWithPicturesCount = 0;
		while($productsWithPicturesCount < $limit) {
			$randomProducts = $this->getRandomProducts(self::DEFAULT_PER_QUERY);
			$i = 0;
			while(($productsWithPicturesCount < $limit) && ($i < self::DEFAULT_PER_QUERY)) {
				$product = $randomProducts[$i];
				if($product->hasPicture() && !in_array($product, $productsWithPictures)) {
					$productsWithPictures[] = $product;
					$productsWithPicturesCount++;
				}
				$i++;
			}
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
			'name'         => 'Заголовок',
			'title'        => 'Расширенный заголовок',
			'description'  => 'Описание',
			'text'         => 'Текст',
			'introtext'    => 'Аннотация',
			'mark'         => 'Марка',
			'price'        => 'Цена',
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
}
