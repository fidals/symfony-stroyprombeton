<?php

namespace App\CatalogBundle\Entity\Repository;

use App\CatalogBundle\Entity\Category;
use Gedmo\Tree\Entity\Repository\ClosureTreeRepository;

class CategoryRepository extends ClosureTreeRepository
{
	public function buildTreeObjects($nodes)
	{
		return $this->buildTreeObjectsChilds($this->buildTreeArray($nodes));
	}

	private function buildTreeObjectsChilds($categories)
	{
		foreach ($categories as &$category) {
			$categoryModel = new Category();
			$children = $category['__children'];
			unset($category['__children']);
			foreach ($category as $property => $value) {
				$method = sprintf('set%s', ucwords($property));
				if(method_exists($categoryModel, $method)) {
					$categoryModel->$method($value);
				}
			}
			if (!empty($children)) {
				$category['__children'] = $this->buildTreeObjectsChilds($children, $categoryModel);
			}
			$category['model'] = $categoryModel;
		}
		return $categories;
	}
}