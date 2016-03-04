<?php

namespace App\AdminBundle\Controller;

use function Functional\filter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait FilterTrait.для удобной работы с фильтрами.
 * Работает независимо от сущности, берет информацию из $filters члена класса контроллера сущности.
 */
trait FilterTrait
{
    /**
     * Метод-обертка для получения применненных фильтров из параметров GET-запроса.
     * Используем метод filter из либы Functional.
     *
     * @param array $GETParameters - массив GET-параметров
     *
     * @return array - массив примененных фильтров.
     */
    protected function getAppliedFilters(array $GETParameters)
    {
        return filter($GETParameters, function ($p, $key) {return !empty($p) && in_array($key, $this->filters);});
    }

    /**
     * Добавляет в dql-запрос фильтрацию.
     *
     * @param $query - изначальный dql-запрос, полученный при помощи QueryBuilder
     * @param array $appliedFilters - список применненых фильтров
     *
     * @return mixed - dql-запрос с примененными фильтрами
     */
    protected function applyFilters($query, array $appliedFilters)
    {
        if (!array_key_exists('isActive', $appliedFilters)) {
            $appliedFilters['isActive'] = 0;
        }

        foreach ($appliedFilters as $field => $value) {
            $this->proceedFilterQuery($query, $field, $value);
        }

        return $query;
    }

    /**
     * Метод-обертка над проверкой были ли применены фильтры.
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function isFiltersApplied(Request $request)
    {
        return $request->get('filter');
    }

    /**
     * Метод-хелпер для конструирования query с примененными фильтрами.
     *
     * @param $query - запрос без фильтров
     * @param string $field - поле, по которому идет фильтрация
     * @param mixed  $value - значение, полученное из формы
     *
     * @return mixed - dql-запрос с применненными фильтрами
     */
    private function proceedFilterQuery($query, $field, $value)
    {
        switch ($field) {
            case 'name':
            case 'translitName':
                $query->andWhere("p.$field LIKE :$field");
                $query->setParameter($field, '%'.$value.'%');
                break;
            default:
                $query->andWhere("p.$field = :$field");
                $query->setParameter($field, $value);
                break;
        }

        return $query;
    }
}
