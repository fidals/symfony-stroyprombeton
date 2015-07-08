<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Order;
use App\CatalogBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
	public function addAction()
	{
		$id = (int) $this->getRequest()->request->getInt('id');
		$quantity = (int) $this->getRequest()->request->getInt('quantity');

		$cartService = $this->get('catalog.cart');
		$cart = $cartService->loadCart();
		$prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
		$product = $prodRp->find($id);
		if(!empty($product)) {
			$cart->addProduct($id, $quantity);
			$cartService->saveCart($cart);
		} else {
			throw new \Exception("Not found product with provided identity");
		}

		return $this->render('AppCatalogBundle:Cart:cart.html.twig');
	}

	public function removeAction()
	{
		$id = (int) $this->getRequest()->request->getInt('id');
		$quantity = (int) $this->getRequest()->request->getInt('quantity');

		$cartService = $this->get('catalog.cart');
		$cart = $cartService->loadCart();

		$cart->removeProduct($id, $quantity);
		$cartService->saveCart($cart);
		return $this->render('AppCatalogBundle:Cart:cart.html.twig');
	}

	public function cleanAction()
	{
		$this->get('catalog.cart')->cleanCart();
		return $this->render('AppCatalogBundle:Cart:cart.html.twig');
	}

	/**
	 * Форма заказа
	 * @return mixed - html-формы или редирект на страницу обработки формы
	 */
	public function orderAction()
	{
		$order = new Order();
		$form = $this->createForm(new OrderType(), $order);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bindRequest($this->getRequest());
			if ($form->isValid()) {
				$mailer = $this->get('mailer');

				$message = \Swift_Message::newInstance()
					->setSubject('Stroyprombeton | New order')
					->setTo('info@stroyprombeton.ru')
					->setFrom('order@stroyprombeton.ru')
					->setContentType("text/html")
					->setBody($this->renderView('AppCatalogBundle:Cart:email.order.html.twig', array(
							'form' => $form->createView(),
							'cart' => $this->get('catalog.cart')->loadCart(true)
						))
					);
				$mailer->send($message);
				$this->get('catalog.cart')->cleanCart();
				return $this->redirect($this->generateUrl('app_main_index'));
			}
		}

		$cart = $this->get('catalog.cart')->loadCart(true);
		return $this->render('AppCatalogBundle:Cart:order.html.twig', array(
			'order' => $cart,
			'form'  => $form->createView()
		));
	}
}