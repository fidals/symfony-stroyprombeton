<?php

namespace App\CatalogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class ProductRepository
 * @package App\CatalogBundle\Entity\Repository
 */
class ProductRepository extends EntityRepository
{
	/**
	 * Константа - лимит позиций на одной странице (для пагинации)
	 */
	const LIMIT = 20,
		UNCAT_PRODUCT_SEARCH_LIMIT = 100;

	/**
	 * Метод поиска для автодополнения
	 *
	 * @param $term
	 * @return array
	 */
	public function searchAutocomplete($term)
	{
		return $this->getEntityManager()->getConnection()->query(
			"SELECT DISTINCT CONCAT(name, ' ', mark) as value FROM products
				WHERE section_id IS NOT NULL
				  HAVING value LIKE ('%" . $term . "%') LIMIT 0, " . self::LIMIT)->fetchAll();
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
		$rsm = new ResultSetMapping();
		$rsm->addEntityResult(self::getClassName(), 'p');
		$classMetadata = $this->getClassMetadata();

		foreach ($classMetadata->fieldMappings as $id => $obj) {
			$rsm->addFieldResult('p', $obj['columnName'], $obj['fieldName']);
		}

		$queryString = 'SELECT * FROM '
			. $classMetadata->table['name']
			. ' WHERE '
			. $classMetadata->columnNames['isHavePhoto']
			. ' = ? ORDER BY RAND() ASC LIMIT ?';

		$query = $this->getEntityManager()
			->createNativeQuery($queryString, $rsm);
		$query->setParameters(array(1, $count));
		return $query->getResult();
	}
}
