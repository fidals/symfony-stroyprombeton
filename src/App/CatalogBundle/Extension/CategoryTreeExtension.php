<?php

namespace App\CatalogBundle\Extension;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class CategoryTreeExtension для получения массива с деревом категорий
 * @package App\CatalogBundle\Extension
 */
class CategoryTreeExtension extends \Twig_Extension
{
	private $categoryRepo;

	public function __construct(EntityManager $entityManager)
	{
		$this->categoryRepo = $entityManager->getRepository('AppCatalogBundle:Category');
	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction(
				'getCategoryTree', array($this, 'getTree')
			),
		);
	}

	public function getName()
	{
		return "category_tree_extension";
	}

	/**
	 * Метод, вызывающийся из шаблона для получения массива-дерева с категориями
	 * @return array дерево Категорий
	 */
	public function getTree()
	{

		$categories = $this->categoryRepo->childrenHierarchy();

		return $categories;
	}


}