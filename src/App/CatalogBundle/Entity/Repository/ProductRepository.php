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
	 * Константа - лимит позиций на одной странице (для пагинации)
	 */
	const LIMIT = 20;
	const UNCAT_PRODUCT_SEARCH_LIMIT = 100;

	/**
	 * Метод поиска для автодополнения
	 *
	 * @param $term
	 * @return array
	 */
	public function searchAutocomplete($term)
	{
		return $this->getEntityManager()->getConnection()->query(
			'SELECT DISTINCT CONCAT(name, \' \', mark) as value FROM products
				WHERE section_id IS NOT NULL
					HAVING value LIKE (\'%' . $term . '%\') LIMIT 0, ' . self::LIMIT)->fetchAll();
	}

	/**
	 * Метод поиска
	 *
	 * @param $term
	 * @param int $page
	 * @param bool $returnObjAsArray
	 * @return array
	 */
	public function search($term, $page = 1, $returnObjAsArray = false)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$offset = ($page - 1) * self::LIMIT;

		$expr = $qb
			->select('p')
			->from(self::getClassName(), 'p')
			->where($qb->expr()->concat('p.name', $qb->expr()->concat($qb->expr()->literal(' '), 'p.mark')) . ' LIKE :term')
			->setFirstResult($offset)
			->setMaxResults(self::LIMIT);

		$query = $expr
			->setParameter('term', '%' . $term . '%');

		return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult();
	}

	public function searchUncat($term, $returnObjAsArray = false)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();

		$expr = $qb
			->select('p')
			->from(self::getClassName(), 'p')
			->where('p.sectionId is NULL')
			->andWhere($qb->expr()->concat('p.name', $qb->expr()->concat($qb->expr()->literal(' '), 'p.mark')) . ' LIKE :term')
			->setMaxResults(self::UNCAT_PRODUCT_SEARCH_LIMIT);

		$query = $expr
			->setParameter('term', '%' . $term . '%');
		return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult();
	}

	public function getRandomProducts($count = self::LIMIT)
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
			->setMaxResults($count)
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
