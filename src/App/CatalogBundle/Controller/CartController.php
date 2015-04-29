<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Order;
use App\CatalogBundle\Entity\Cart;
use App\CatalogBundle\Entity\Repository\CartRepository;
use App\CatalogBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
	/**
	 * CRUD корзины
	 * @return Response
	 */
	public function indexAction()
	{
		$query = $this->getRequest()->query;
		$mode = $query->get('mode');
		$cartRp = CartRepository::getInstance($this);
		$res = new Response();

		if ($mode == 'add') {
			$code = (int)$query->get('code');
			$rest = (int)$query->get('rest');

			$cart = $cartRp->loadCart();
			$cart->addProduct($code, $rest);
			$cartRp->saveCart($cart);

			$res = new Response($cart->getTotalProductsCount());
		} elseif ($mode == 'edit') {
			$order = $query->get('order_basket');
			$cart = $cartRp->loadCart();
			if (!empty($order)) {
				$parts = explode('-', $order);
				foreach ($parts as $part) {
					$props = explode(':', $part);
					$cart->addProduct((int)$props[0], (int)$props[1]);
				}
				$cartRp->saveCart($cart);
			}
			$res = new Response($cart->serialize());
		} elseif ($mode == 'clear') {
			$cartRp->cleanCart();
		}

		return $res;
	}

	/**
	 * Форма заказа
	 * @return mixed - html-формы или редирект на страницу обработки формы
	 */
	public function orderAction(Request $request)
	{
		$order = new Order();
		$form = $this->createForm(new OrderType(), $order);

		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);
			if ($form->isValid()) {
				$mailer = $this->get('mailer');

				$message = \Swift_Message::newInstance()
					->setSubject('Stroyprombeton | New order')
					->setTo($this->container->getParameter('parameters.app_catalog.order_mail.to'))
					->setFrom($this->container->getParameter('parameters.app_catalog.order_mail.from'))
					->setContentType("text/html")
					->setBody($this->renderView('AppCatalogBundle:Cart:email.order.html.twig', array(
							'form' => $form->createView(),
							'cart' => CartRepository::getInstance($this)->loadCart(true)
						))
					);
				$mailer->send($message);
				CartRepository::getInstance($this)->cleanCart();
				return $this->redirect($this->generateUrl('app_main_index'));
			}
		}

		return $this->render('AppCatalogBundle:Cart:order.html.twig', array(
			'order' => CartRepository::getInstance($this)->loadCart(true),
			'form' => $form->createView()
		));
	}
}