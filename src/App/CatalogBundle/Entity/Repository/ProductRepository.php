<?php

namespace App\CatalogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий продукта
 *
 * Class ProductRepository
 * @package App\CatalogBundle\Entity\Repository
 */
class ProductRepository extends EntityRepository
{


    /************************************************/
    /**
     * Лимит на пагинацию
     */
    const PAGINATION_LIMIT = 12;
    /**
     * Лимит на пагинацию бестселлеров
     */
    const BESTSELLERS_LIMIT = 3;
    /**
     * Лимит на пагинацию популярных категорий
     */
    const POPULAR_CATEGORIES_LIMIT = 10;
    /**
     * Выполняет выборку всех кортежей в сортированном виде
     *
     * @param $field
     * @param string $direction
     * @return array
     */
    public function findAllSortedBy($field, $direction = 'ASC')
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $expr = $qb
            ->select('p')
            ->from(self::getClassName(), 'p')
            ->orderBy('p.' . $field, $direction);

        return $expr->getQuery()->getResult();
    }
    /**
     * Возвращает бестселлеры (продукты с большим количеством просмотров)
     *
     * @return array
     */
    public function getBestsellers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $expr = $qb
            ->select('p')
            ->from(self::getClassName(), 'p')
            ->where('p.monthViews != 0')
            ->orderBy('p.monthViews', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(self::BESTSELLERS_LIMIT);

        return $expr->getQuery()->getResult();
    }
    /**
     * Возвращает популярные категории (по просмотрам в месяц)
     *
     * @return array
     */
    public function getPopularCatsData()
    {
        $dqlQuery = 'SELECT p, MIN(p.price) as minPrice
                     FROM AppCatalogBundle:Product p
                     GROUP BY p.categoryId
                     ORDER BY p.monthViews DESC';

        $data = $this->getEntityManager()
            ->createQuery($dqlQuery)
            ->setMaxResults(self::POPULAR_CATEGORIES_LIMIT)
            ->getResult();

        foreach($data as $row) {
            $retArr[] = array(
                'minPrice'    => $row['minPrice'],
                'category' => $row[0]->getCategory()
            );
        }
        return  $data;
    }

    public function getProductsByBrandId($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $device = $qb
            ->select('d')
            ->from('AppCatalogBundle:Series','s')
            ->join('AppCatalogBundle:Device','d')
            ->where('s.trademarkId = :id')
            ->setParameter('id', $id )
            ->setFirstResult(0);
        $devices = $device->getQuery()->getResult();
        $products = $qb
            ->select('DISTINCT p')
            ->from('AppCatalogBundle:Product','p')
            ->join($devices,'d')
            ->orderBy('p.name')
            ->setFirstResult(0);
        return $products->getQuery()->getResult();
    }
    /*
     * отбор продуктов с пагинацией

    public function getProductsByPages()
    {
        $qb=$this->getEntityManager()->createQueryBuilder();
        $product= $qb
            ->select('d')
            ->from('AppCatalogBundle:Product','pr')
            ->
    }
    */
    /**
     * Выполняет поиск (для автодополнения)
     *
     * @param $column
     * @param $term
     * @param bool $returnObjAsArray
     * @return array
     */
    public function searchAutocomplete($column, $term, $returnObjAsArray = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $expr = $qb
            ->select('DISTINCT p.name as value')
            ->from(self::getClassName(), 'p')
            ->where('p.' . $column . ' LIKE :term')
            ->setFirstResult(0)
            ->setMaxResults(self::PAGINATION_LIMIT);

        $query = $expr
            ->setParameter('term', '%' . $term . '%');

        return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult() ;
    }
    /**
     * Выполняет поиск
     *
     * @param $column
     * @param $term
     * @param int $page
     * @param bool $returnObjAsArray
     * @return array
     */
    public function search($column, $term, $returnObjAsArray = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
       /* $offset = ($page - 1) * self::PAGINATION_LIMIT; */
        $expr = $qb
            ->select('p')
            ->from(self::getClassName(), 'p')
            ->where('p.' . $column . ' LIKE :term');
        /*     ->setFirstResult($offset);
           ->setMaxResults(self::PAGINATION_LIMIT); */

        $query = $expr
            ->setParameter('term', '%' . $term . '%');

        return ($returnObjAsArray) ? $query->getQuery()->getArrayResult() : $query->getQuery()->getResult() ;
    }
    /**
     * Возвращает количество элементов подходящих под критерий поиска (LIKE $term)
     *
     * @param $column
     * @param $term
     * @return mixed
     */
    public function getCount($column, $term)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $expr = $qb
            ->select('COUNT(p)')
            ->from(self::getClassName(), 'p')
            ->where('p.' . $column . ' LIKE :term');

        $query = $expr
            ->setParameter('term', '%' . $term . '%');

        return $query->getQuery()->getSingleScalarResult();
    }
   /*
        public function getCountList($column, $term)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $expr = $qb
            ->select('COUNT(p)')
            ->from(self::getClassName(), 'p')
            ->where('p.' . $column . ' LIKE :term');

        $query = $expr
            ->setParameter('term', '%' . $term . '%');

        return $query->getQuery()->getSingleScalarResult();
    }
    */


}