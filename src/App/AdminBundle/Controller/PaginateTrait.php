<?php

namespace App\AdminBundle\Controller;

/**
 * Class PaginateTrait - трейт для удобной работы с пагинацией.
 */
trait PaginateTrait
{
    /**
     * Количество сущностей на одной странице пагинации.
     *
     * @var int
     */
    private $objectsPerPage = 10;

    /**
     * Функция-обертка над KnpPaginator.
     *
     * @param int $page - страница, на которой сейчас пользователь.
     * @param $source - источник для пагинации, как правило, объект DQL
     */
    protected function paginate($page, $source)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $source,
            $page,
            $this->objectsPerPage);

        return $pagination;
    }
}
