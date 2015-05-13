<?php

namespace App\CatalogBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\ClosureTreeRepository;

class CategoryRepository extends ClosureTreeRepository
{
	public function isParent($category, $catUrl)
	{
		$parents = $this->getPath($category);
		foreach ($parents as $parent) {
			if ($parent->getAlias() == $catUrl)
				return true;
		}
		return false;
	}
}