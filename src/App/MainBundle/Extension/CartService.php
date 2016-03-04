<?php

namespace App\MainBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\MainBundle\Entity\Cart;

/**
 * Class CartRepository
 * Репозиторий для работы с корзиной
 * Не имеет никаких связей с Doctrine ORM и вообще с СУБД.
 * Просто предоставляет удобный интерфейс для работы с сессией применительно к корзине.
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
     *
     * @return Cart
     */
    public function loadCart($withProducts = false)
    {
        $cart = $this->session->get('cart', false);

        if ($withProducts && $cart) {
            $cart = clone $cart;
            $prodRp = $this->doctrine->getRepository('AppMainBundle:Product');
            $products = $cart->getProducts();
            $cartProducts = array();
            foreach ($products as $id => $count) {
                $model = $prodRp->find($id);
                $cartProducts[$id] = array('count' => $count, 'model' => $model);
            }
            $cart->setProducts($cartProducts);
        }

        return ($cart) ? clone $cart : new Cart();
    }

    /**
     * Метод для получения данных продуктов для подготовки JSON'а.
     *
     * @return array данные продуктов, нужные для отрисовки заказа.
     */
    public function getProductsInfo()
    {
        $router = $this->container->get('router');
        $cart = $this->loadCart(true);
        $products = array();

        foreach ($cart->getProducts() as $product) {
            $products[] = array(
                'id' => $product['model']->getId(),
                'count' => $product['count'],
                'url' => $router->generate('app_catalog_product', array(
                    'id' => $product['model']->getId(),
                )),
                'name' => $product['model']->getName(),
                'price' => $product['model']->getPrice(),
                'nomen' => $product['model']->getNomen(),
            );
        }

        return $products;
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
