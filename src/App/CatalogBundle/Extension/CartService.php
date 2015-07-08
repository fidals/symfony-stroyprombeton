<?php

namespace App\CatalogBundle\Extension;

use App\CatalogBundle\Command\SitemapCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\CatalogBundle\Entity\Cart;

/**
 * Class CartRepository
 * Репозиторий для работы с корзиной
 * Не имеет никаких связей с Doctrine ORM и вообще с СУБД.
 * Просто предоставляет удобный интерфейс для работы с сессией применительно к корзине.
 *
 * @package App\CatalogBundle\Entity\Repository
 */
class CartService
{
	/**
	 * @var \Doctrine\Bundle\DoctrineBundle\Registry
	 */
	protected $doctrine;
	/**
	 * @var null|\Symfony\Component\HttpFoundation\Session\SessionInterface
	 */
	protected $session;

	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->doctrine = $container->get('doctrine');
		$this->session = $container->get('session');
	}

	// cart methods
	/**
	 * @param bool $withProducts - корзина с продуктами, или нет
	 * @return Cart
	 */
	public function loadCart($withProducts = false)
	{
		$cart = $this->session->get('cart', false);

		if ($withProducts && $cart) {
			$cart = clone $cart;
			$prodRp = $this->doctrine->getRepository('AppCatalogBundle:Product');
			$catRp = $this->doctrine->getRepository('AppCatalogBundle:Category');
			$products = $cart->getProducts();
			$cartProducts = array();
			foreach ($products as $id => $count) {
				$model = $prodRp->find($id);
				$catUrl = SitemapCommand::$baseCats[$catRp->getPath($model->getCategory())[0]->getId()];
				$cartProducts[$id] = array('count' => $count, 'model' => $model, 'catUrl' => $catUrl);
			}
			$cart->setProducts($cartProducts);
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
