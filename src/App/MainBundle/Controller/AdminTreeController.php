<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\CatalogBundle\Entity\Repository\CategoryRepository;

class AdminTreeController extends Controller
{
	/**
	 * Возвращает дерево категорий в формате JSON:
	 */
	public function buildCategoryTreeAction(Request $request)
	{
		$catRp          = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$catHierarchy   = $catRp->childrenHierarchy();
		$categoriesTree = $catRp->buildCategoryTree($catHierarchy);

		return new JsonResponse($categoriesTree);
	}

	/**
	 * Возвращает товары по 'id' по хэшу из URL:
	 */
	public function getProductsByCategoryIdAction(Request $request)
	{
		$categoryId = $request->get('id');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

		$category = $catRp->find($categoryId);
		$products = array();

		foreach ($category->getProducts() as $item) {
			$productAttrs = array(
				'data-id' => $item->getId()
			);

			$productTree = array(
				'text'   => '[' . $item->getId() . '] ' . $item->getName(),
				'a_attr' => array(
					'data-id' => $item->getId()
				)
			);

			$products[] = (object) $productTree;
		}

		return new JsonResponse($products);
	}
}