<?php

namespace App\CatalogBundle\Extension;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class TreeMenuExtension для получения массива с деревом категорий
 * @package App\CatalogBundle\Extension
 */
class TreeMenuExtension extends \Twig_Extension
{
	private $categoryRepo;
	private $router;

	public function __construct(EntityManager $entityManager, Router $router)
	{
		$this->categoryRepo = $entityManager->getRepository('AppCatalogBundle:Category');
		$this->router = $router;
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
		return "tree_menu_extension";
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