<?php

namespace App\MainBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository
 * @package App\MainBundle\Entity\Repository
 */
class PostRepository extends EntityRepository
{
	public function findAllQuery()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		return $qb->select('p')->from(self::getClassName(), 'p')->orderBy('p.date', 'DESC');
	}
}