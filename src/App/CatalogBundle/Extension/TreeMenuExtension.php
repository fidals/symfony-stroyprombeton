<?php

namespace App\CatalogBundle\Extension;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class TreeMenuExtension для отрисовки дерева категории в шаблонах
 * @package App\CatalogBundle\Extension
 */
class TreeMenuExtension extends \Twig_Extension
{
	const DEFAULT_CSS_CLASS = "category-tree";
	const MAX_DEPTH = 50;
	const STARTING_DEPTH = 1;

	/**
	 * html-дерево категорий нужной глубины
	 * @var string
	 */
	protected $htmlTree;

	/**
	 * Переданный из шаблона css-класс для <ul>-списков
	 * @var string
	 */
	protected $cssClass;

	/**
	 * Нужная глубина дерева, должна быть <= MAX_DEPTH
	 * @var integer
	 */
	protected $treeDepth;

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
				'buildCategoryTree', array($this, 'getTree'),
				array('is_safe' => array('html'))
			),
		);
	}

	public function getName()
	{
		return "tree_menu_extension";
	}

	/**
	 * Метод, вызывающийся из шаблона для получения html-дерева заданной глубины с заданным css-классом
	 *
	 * @param integer $depth переданная из шаблона глубина дерева
	 * @param string $cssClass строка с нужным css-классом для <ul>-элементов
	 *
	 * @return string html-дерева для отображения в шаблоне
	 */
	public function getTree($depth = self::MAX_DEPTH, $cssClass = self::DEFAULT_CSS_CLASS)
	{
		if ($depth > self::MAX_DEPTH) {
			throw new InvalidArgumentException('Depth for tree rendering cannot be more than ' . self::MAX_DEPTH);
		}

		$this->treeDepth = $depth;
		$this->cssClass = $cssClass;
		$categories = $this->categoryRepo->childrenHierarchy();

		$this->htmlTreeBuild(self::STARTING_DEPTH, $categories);

		return $this->htmlTree;
	}

	/**
	 * Метод для построения дерева категорий.
	 * Рекурсивно обходим категории до нужной глубины и сохраняем html-дерево в свойстве класса $htmlTree.
	 *
	 * @param integer $currentDepth глубина дерева в цепочке вызовов
	 * @param array $categories список категорий для построения ветви дерева
	 *
	 */
	private function htmlTreeBuild($currentDepth, $categories)
	{
		$ulClass = $this->cssClass . "-depth-" . $currentDepth;
		$this->htmlTree .= "<ul class=" . $ulClass . ">";

		foreach ($categories as $cat) {
			$routeToCategory = $this->router->generate('app_catalog_category', array('id' => $cat['id']));
			$link = "<a href='" . $routeToCategory . "'>" . $cat['name'] . "</a>";
			$this->htmlTree .= "<li>" . $link;

			if (($currentDepth < $this->treeDepth) && !empty($cat['__children'])) {
				$this->htmlTreeBuild($currentDepth + 1, $cat['__children']);
			}

			$this->htmlTree .= "</li>";
		}

		$this->htmlTree .= "</ul>";
	}
}