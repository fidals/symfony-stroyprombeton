<?php

namespace App\CatalogBundle\Extension;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;

/**
 * Class TreeMenuExtension для отрисовки дерева категории в шаблонах
 * @package App\CatalogBundle\Extension
 */
class TreeMenuExtension extends \Twig_Extension
{
	private $categoryRepo;
	const MAX_DEPTH = 50;
	const STARTING_DEPTH = 1;

	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->categoryRepo = $em->getRepository('AppCatalogBundle:Category');
	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('tree', array($this, 'getTree'))
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
	 * @return string html-дерева для raw-отображения в шаблоне
	 */
	public function getTree($depth, $cssClass)
	{
		if ($depth > self::MAX_DEPTH) {
			throw new InvalidArgumentException('Depth for tree rendering cannot be more than ' . self::MAX_DEPTH);
		}

		$categories = $this->categoryRepo->childrenHierarchy();
		$tree = $this->treeBuild($depth, self::STARTING_DEPTH, $categories, $tree, $cssClass);

		return $tree;
	}

	/**
	 * Метод для построения дерева категорий
	 *
	 * @param integer $depth запрашиваемая глубина дерева категорий
	 * @param integer $currentDepth глубина дерева в цепочке вызовов
	 * @param array $categories список категорий для построения ветви дерева
	 * @param string $tree строка с html-деревом, изменяющаяся от вызова к вызову, передается по ссылке
	 * @param string $cssClass строка с переданным из шаблона css-классом для <ul>-элементов
	 *
	 * @return string html-дерева для raw-отображения в шаблоне
	 */
	private function treeBuild($depth, $currentDepth, $categories, &$tree, $cssClass) {

		$ulClass = $cssClass . "-depth-" . $currentDepth;
		$tree .= "<ul class=" . $ulClass . ">";

		foreach ($categories as $cat) {
			$anchor = "<a href='/gbi/category/" . $cat['id']  . "'>" . $cat['name'] . "</a>";
			$tree .= "<li>" . $anchor . "</li>";

			if ($currentDepth + 1 <= $depth) {
				$this->treeBuild($depth, $currentDepth + 1, $cat['__children'], $tree, $cssClass);
			}
		}

		$tree .= "</ul>";

		return $tree;
	}
}