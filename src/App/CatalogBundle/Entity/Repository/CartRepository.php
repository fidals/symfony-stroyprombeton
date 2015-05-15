<?php

namespace App\CatalogBundle\Entity\Repository;

use App\CatalogBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CartRepository
 * Репозиторий для работы с корзиной
 * Не имеет никаких связей с Doctrine ORM и вообще с СУБД.
 * Просто предоставляет удобный интерфейс для работы с сессией применительно к корзине.
 *
 * @package App\CatalogBundle\Entity\Repository
 */
class CartRepository
{
	/**
	 * @var \Doctrine\Bundle\DoctrineBundle\Registry
	 */
	protected $doctrine;
	/**
	 * @var null|\Symfony\Component\HttpFoundation\Session\SessionInterface
	 */
	protected $session;
	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @param Controller $controller
	 * @return CartRepository
	 */
	public static function getInstance(Controller $controller)
	{
		if (null === self::$_instance) {
			self::$_instance = new self($controller);
		}
		return self::$_instance;
	}

	/**
	 * @param Controller $controller
	 */
	public function __construct(Controller $controller)
	{
		$this->doctrine = $controller->getDoctrine();
		$this->session = $controller->getRequest()->getSession();
	}

	// cart methods
	/**
	 * @param bool $with_products - корзина с продуктами, или нет
	 * @return Cart
	 */
	public function loadCart($with_products = false)
	{
		$cart = $this->session->get('cart', false);

		if ($with_products && $cart) {
			$cart = clone $cart;
			$prodRp = $this->doctrine->getRepository('AppCatalogBundle:Product');
			$products = $cart->getProducts();
			$cart_products = array();
			foreach ($products as $id => $count) {
				$cart_products[$id] = array('count' => $count, 'model' => $prodRp->find($id));
			}
			$cart->setProducts($cart_products);
		}
		return ($cart) ? clone $cart : new Cart();
	}

	/**
	 * @param Cart $cart
	 */
	public function saveCart(Cart $cart)
	{
		$this->session->set('cart', $cart);
	}

	/**
	 *
	 */
	public function cleanCart()
	{
		$this->session->remove('cart');
	}
}
