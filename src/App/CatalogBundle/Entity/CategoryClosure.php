<?php

namespace App\CatalogBundle\Entity;

use Gedmo\Tree\Entity\MappedSuperclass\AbstractClosure;
use Doctrine\ORM\Mapping as ORM;

/**
 * Необходим для реализации древовидной структуры данных для Category
 * Подробнее здесь https://github.com/Atlantic18/DoctrineExtensions
 * Сущность отображает связи сущностей в БД (таблица category_closures)
 *
 * @ORM\Table(name="category_closures")
 * @ORM\Entity
 */
class CategoryClosure extends AbstractClosure {}