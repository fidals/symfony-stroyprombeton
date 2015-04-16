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

        return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult() ;
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
        //$qts =$query->__toString();
        //die();
        return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult() ;
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
            ->andWhere('p.isHavePhoto = 1')
            ->orderBy('p.id')
            ->setMaxResults($count)
            ->setParameter('rand', rand(0, $max - 10000))
            ->getQuery()
            ->getResult();
    }
}
