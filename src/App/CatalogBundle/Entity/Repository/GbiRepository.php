<?php

namespace App\CatalogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class GbiRepository extends EntityRepository
{
    public function findAllOrderedBy($column, $direction)
    {
        return $this->getEntityManager()->createQuery('SELECT p FROM AppCatalogBundle:Gbi p ORDER BY p.' . $column . ' ' . $direction)->getResult();
    }
}
