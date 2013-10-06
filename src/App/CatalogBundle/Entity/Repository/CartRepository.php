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
        if(null === self::$_instance) {
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
     * @param bool $fillModels
     * @return Cart|mixed
     */
    public function loadCart($fillModels = false)
    {
        $cart = $this->session->get('cart', false);

        if($fillModels && $cart) {
            $cart = clone $cart;
            $prodRp = $this->doctrine->getRepository('AppCatalogBundle:Product');
            $products = $cart->getProducts();
            foreach($products as $productId => $count) {
                $filledProducts[$productId] = array('count' => $count, 'model' => $prodRp->find($productId));
            }
            $cart->setProducts($filledProducts);
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
