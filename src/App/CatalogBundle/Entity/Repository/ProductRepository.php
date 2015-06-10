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
	const LIMIT = 20;
	const UNCAT_PRODUCT_SEARCH_LIMIT = 100;

	/**
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

	public function getRandomProducts($limit = self::DEFAULT_LIMIT)
	{
		$max = $this->getEntityManager()
			->createQueryBuilder()
			->select('MAX(p.id)')
			->from(self::getClassName(), 'p')
			->getQuery()
			->getSingleScalarResult();

		return $this->getEntityManager()
			->createQueryBuilder()
			->select('p')
			->from(self::getClassName(), 'p')
			->where('p.id >= :rand')
			->orderBy('p.id')
			->setMaxResults($limit)
			->setParameter('rand', rand(0, $max - 10000))
			->getQuery()
			->getResult();
	}

	/**
	 * Возвращает массив свойств для TableGear
	 *
	 * @return array
	 */
	public function getTableGearProperties()
	{
		return [
			'name'                      => 'Заголовок',
			'title'                     => 'Расширенный заголовок',
			'description'               => 'Описание',
			'annotation'                => 'Аннотация',
			'mark'                      => 'mark',
			'price_coefficient'         => 'Коэффициент цены',
			'price'                     => 'Цена',
			'nomen'                     => 'Код',
			'length'                    => 'Длина (мм)',
			'width'                     => 'Ширина (мм)',
			'heigth'                    => 'Высота (мм)',
			'weight'                    => 'Масса (кг)',
			'volume'                    => 'Объем (м3)',
			'diameter_in'               => 'Внутренний диаметр (мм)',
			'diamenter_out'             => 'Внешний диаметр (мм)',
			'link_to_stkmetal_category' => 'Ссылка на соответствующую категорию на stk-metal'
		];
	}
}
