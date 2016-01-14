<?php

namespace App\MainBundle\Entity\Repository;

use App\MainBundle\Entity\Category;
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
			$category['model'] = $this->find($category['id']);
		}
		return $categories;
	}

	/**
	 * Рекурсивно собирает и возвращает категории для дерева категорий в Админке:
	 *
	 * @param $array - массив всех категорий со всем набором свойств;
	 */
	public function buildCategoryTree($array)
	{
		$result = array();

		foreach ($array as $item => $prop) {
			$arrayTree = array(
				'id'     => $prop['id'],
				'text'   => '[' . $prop['id'] . '] ' . $prop['name'],
				'a_attr' => array(
					'data-id' => $prop['id']
				)
			);

			if (!empty($prop['__children'])) {
				$children = $this->buildCategoryTree($prop['__children']);

				$arrayTree['children'] = $children;
			} else {
				$arrayTree['children'] = true;
			}

			$result[] = (object) $arrayTree;
		}

		return $result;
	}
}