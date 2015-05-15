<?php

namespace App\CatalogBundle\Entity;

use Gedmo\Tree\Entity\MappedSuperclass\AbstractClosure;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="category_closures")
 * @ORM\Entity
 */
class CategoryClosure extends AbstractClosure
{
}