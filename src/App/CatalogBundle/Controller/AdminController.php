<?php
// some comments
namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Product;
use App\CatalogBundle\Extension\AjaxError;
use App\CatalogBundle\Extension\AjaxSuccess;
use App\CatalogBundle\Extension\TableGear;
use App\CatalogBundle\Form\CategoryType;
use App\CatalogBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class AdminController extends Controller
{
	public $dProds = 0;
	public $dCats = 0;

	public function indexAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$rootNodes = $catRp->getRootNodes();

		// fill childs
		foreach ($rootNodes as $rootNode) {
			$rootChilds[$rootNode->getId()] = $catRp->children($rootNode, true);
		}

		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');

		return $this->render('AppCatalogBundle:Admin:index.html.twig',
			array(
				'parentCategory' => false,
				'rootCategories' => $rootNodes,
				'rootChilds' => $rootChilds
			)
		);
	}

	public function categoryChildsAction()
	{
		$categoryId = (int)$this->getRequest()->query->get('categoryId');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);

		if (!empty($category)) {
			$categoryChilds = $catRp->getChildren($category, true);
			if (!empty($categoryChilds)) {

				$categoryChildrensHtml = $this->renderView('AppCatalogBundle:Admin:ul.childs.category.html.twig',
					array(
						'category' => $category,
						'categoryChilds' => $categoryChilds
					)
				);

				return new AjaxSuccess(
					array('html' => $categoryChildrensHtml)
				);
			}
		}

		return new AjaxError();
	}

	public function categoryProductsAction()
	{
		$categoryId = (int)$this->getRequest()->query->get('categoryId');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);

		if (!empty($category)) {
			$categoryProductsHtml = $this->renderView('AppCatalogBundle:Admin:ul.childs.product.html.twig',
				array(
					'category' => $category
				)
			);

			return new AjaxSuccess(
				array('html' => $categoryProductsHtml)
			);
		}

		return new AjaxError();
	}

	public function categoryParentAction()
	{
		$parentId = (int) $this->getRequest()->request->get('parentId');
		$categoryId = (int) $this->getRequest()->request->get('childId');

		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);

		if (!empty($category)) {
			$em = $this->getDoctrine()->getEntityManager();
			$category->setParent(($parentId !== 0) ? $catRp->find($parentId) : null);
			$em->flush();
			$em->clear();
			return new AjaxSuccess();
		} else {
			return new AjaxError();
		}
	}

	public function getCategoryAction()
	{
		$categoryId = (int)$this->getRequest()->query->get('categoryId');

		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);
		if (!empty($category)) {
			$form = $this->createForm(new CategoryType(), $category);
			return new AjaxSuccess(array(
				'html' => $this->renderView('AppCatalogBundle:Form:category.html.twig', array(
						'form' => $form->createView()
					)
				)
			));
		}
		return new AjaxError();
	}

	public function saveCategoryAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

		$form = $this->createForm(new CategoryType(), new Category());
		$form->bind($this->getRequest());

		$categoryId = $form->getData()->getId();
		$category = $catRp->find($categoryId);

		if (!empty($category)) {
			$form = $this->createForm(new CategoryType(), $category);
			$form->bind($this->getRequest());
		}

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($form->getData());
			$em->flush();
			return new AjaxSuccess();
		}
		return new AjaxError();
	}

	public function saveProductAction()
	{
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');

		$form = $this->createForm(new ProductType(), new Product());
		$productId = $form->bind($this->getRequest())->getData()->getId();

		$product = $catRp->find($productId);

		if (!empty($product)) {
			$form = $this->createForm(new ProductType(), $product);
			$form->bind($this->getRequest());
		}

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($form->getData());
			$em->flush();
			return new AjaxSuccess();
		}
		return new AjaxError();
	}

	public function deleteCategoryAction()
	{
		$categoryId = (int)$this->getRequest()->request->get('categoryId');

		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($categoryId);

		if (!empty($category)) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($category);
			$em->flush();
			return new AjaxSuccess();
		}
		return new AjaxError();
	}

	public function deleteProductAction()
	{
		$productId = (int) $this->getRequest()->request->get('productId');

		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$product = $prodRp->find($productId);

		if (!empty($product)) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($product);
			$em->flush();
			return new AjaxSuccess();
		}
		return new AjaxError();
	}

	public function getBlankCategoryAction()
	{
		$parentCategoryId = (int)$this->getRequest()->request->get('parentCategoryId');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$parentCategory = $catRp->find($parentCategoryId);
		if (!empty($parentCategory)) {
			$category = new Category();
			$category->setParent($parentCategory);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($category);
			$em->flush();
			$form = $this->createForm(new CategoryType(), $category);
			return new AjaxSuccess(array(
				'html' => $this->renderView('AppCatalogBundle:Form:category.html.twig', array(
						'form' => $form->createView()
					)
				)
			));
		}
		return new AjaxError();
	}

	public function getBlankProductAction()
	{
		$parentCategoryId = (int)$this->getRequest()->request->get('parentCategoryId');
		$parentCategory = $this->getDoctrine()->getRepository('AppCatalogBundle:Category')->find($parentCategoryId);
		if (!empty($parentCategory)) {
			$product = new Product();
			$product->setCategory($parentCategory);
			$product->setName('Название продукта');
			$product->setMark('Марка');
			$product->setIsActive(true);
			$product->setIsNewPrice(true);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($product);
			$em->flush();
			$form = $this->createForm(new ProductType(), $product);
			return new AjaxSuccess(array(
				'html' => $this->renderView('AppCatalogBundle:Form:product.html.twig', array(
						'form' => $form->createView()
					)
				)
			));
		}
		return new AjaxError();
	}

	public function getProductAction()
	{
		$productId = (int) $this->getRequest()->query->get('productId');

		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$product = $prodRp->find($productId);
		if (!empty($product)) {
			$form = $this->createForm(new ProductType(), $product);
			return new AjaxSuccess(array(
				'html' => $this->renderView('AppCatalogBundle:Form:product.html.twig', array(
						'form' => $form->createView()
					)
				)
			));
		}
		return new AjaxError();
	}

	public function searchUncatProductAction()
	{
		$condition = $this->getRequest()->query->get('condition');

		if (!empty($condition)) {
			$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
			$products = $prodRp->searchUncategorized($condition);
			$categoryProductsHtml = $this->renderView('AppCatalogBundle:Admin:search.product.html.twig',
				array(
					'products' => $products
				)
			);

			return new AjaxSuccess(
				array('html' => $categoryProductsHtml)
			);
		}
		return new AjaxError();
	}

	public function productCategoryAction()
	{
		$productId = (int) $this->getRequest()->request->get('productId');
		$categoryId = (int) $this->getRequest()->request->get('categoryId');

		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$em = $this->getDoctrine()->getEntityManager();
		$product = $prodRp->find($productId);
		$category = $catRp->find($categoryId);
		if (!empty($product) && !empty($category)) {
			$product->setCategory($category);
			$em->flush();
			return new AjaxSuccess();
		} elseif (!empty($product) && $categoryId === 0) {
			$product->setCategory(null);
			$em->flush();
			return new AjaxSuccess();
		}
		return new AjaxError();
	}

	public function sandboxAction()
	{
		/*
				// delete cats 537 and 540

				$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
				$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
				$em = $this->getDoctrine()->getEntityManager();

				$categories = array(537, 540);
				foreach($categories as $categoryId) {
					$category = $catRp->find($categoryId);
					$tree = $catRp->buildTreeArray($catRp->getNodesHierarchy($category, false));

					foreach($tree as $node) {
						$this->recursiveDelete($node, $catRp, $prodRp, $em);
					}
				}
				$em->flush();
				die('success dProds = ' . $this->dProds . ' dCats = ' . $this->dCats);*/


		$catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$em = $this->getDoctrine()->getEntityManager();

		$obj = \PHPExcel_IOFactory::load('/var/www/mtl.xls');
		$sc = $obj->getSheetCount();
		// loop all sheets
		for ($i = 1; $i < $sc; $i++) {
			$obj->setActiveSheetIndex($i);
			$aSheet = $obj->getActiveSheet();

			$rcid = 537;
			$baseCatNum = 1;
			$subCat1Num = 0;
			$subCat2Num = 0;
			$baseCatNomen = 0;
			$subCat1Nomen = 0;
			$subCat2Nomen = 0;

			$prodNum = 0;

			foreach ($aSheet->getRowIterator() as $row) {
				$cellIterator = $row->getCellIterator();
				foreach ($cellIterator as $cell) {
					$rowArr[] = $cell->getCalculatedValue();
				}
				$nomen = (int)$rowArr[0];
				$baseCatNum = floor($nomen / 10000000);
				$subCat1Num = floor(($nomen % 10000000) / 100000);
				$subCat2Num = floor(($nomen % 100000) / 1000);
				$prodNum = $nomen % 1000;

				if ($prodNum == 0) {
					if ($subCat2Num == 0) {

						// create subcat1
						$cat = new Category();
						$cat->setTitle(substr($rowArr[1], 2));
						$cat->setNomen($nomen);
						$cat->setParent($catRp->find($rcid));

						$subCat1Nomen = $nomen;
						$em->persist($cat);
						$em->flush();
					} else {

						// create subcat2
						$cat = new Category();
						$cat->setTitle(substr($rowArr[1], 5));
						$cat->setNomen($nomen);
						$cat->setParent($catRp->findByNomen($subCat1Nomen));

						$subCat2Nomen = $nomen;
						$em->persist($cat);
						$em->flush();
					}
				} else {

					// insert product
					$product = new Product();
					$product->setNomen($nomen);
					$product->setCategory($catRp->findByNomen($subCat2Nomen));

				}

				die();
			}
		}
	}

	public function recursiveDelete($catArr, $catRp, $prodRp, $em)
	{
		if (!empty($catArr)) {
			if (!isset($catArr['id'])) {
				foreach ($catArr as $node) {
					if (!isset($node['__children']) && !empty($node['__children'])) {
						$this->recursiveDelete($node['__children'], $catRp, $prodRp, $em);
					}

					$category = $catRp->find($node['id']);
					$categoryProducts = $prodRp->findBySectionId($category->getId());

					if (!empty($categoryProducts)) {
						foreach ($categoryProducts as &$categoryProduct) {
							$em->remove($categoryProduct);
							$this->dProds++;
						}
					}
					$em->remove($category);
					$this->dCats++;
				}
			} else {
				$category = $catRp->find($catArr['id']);
				$categoryProducts = $prodRp->findBySectionId($category->getId());

				if (!empty($categoryProducts)) {
					foreach ($categoryProducts as &$categoryProduct) {
						$em->remove($categoryProduct);
						$this->dProds++;
					}
				}
				$em->remove($category);
				$this->dCats++;
			}
		}
	}

	public function editProductsAction()
	{
		$tableGear = new TableGear($this->container);
		return $this->render('AppCatalogBundle:Admin:edit_products.html.twig', array(
			'tablegear_content' => $tableGear->getContent(),
			'admin_pool'        => $this->container->get('sonata.admin.pool')
		));
	}
}
