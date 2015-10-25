<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Order;
use App\CatalogBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
	public function addAction(Request $request)
	{
		$id = $request->request->getInt('id');
		$quantity = $request->request->getInt('quantity');

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

	public function removeAction(Request $request)
	{
		$id = $request->request->getInt('id');
		$quantity = $request->request->getInt('quantity');

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
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function orderAction(Request $request)
	{
		$form = $this->createForm(new OrderType(), null, array('csrf_protection' => false));

		if ($request->getMethod() == 'POST') {

			$form->handleRequest($request);

			if ($form->isValid()) {
				$mailer = $this->get('mailer');

				$recipients = array(
					$this->container->getParameter('email_info'),
					$form['email']->getData()
				);

				$body = $this->renderView('AppCatalogBundle:Cart:email.order.html.twig', array(
					'form' => $form->createView(),
					'cart' => $this->get('catalog.cart')->loadCart(true)
				));

				$message = \Swift_Message::newInstance()
					->setSubject('Stroyprombeton | New order')
					->setTo($recipients)
					->setFrom($this->container->getParameter('email_order'))
					->setContentType("text/html")
					->setBody($body)
					->attach(\Swift_Attachment::fromPath($attach));

				$mailer->send($message);

				$this->get('catalog.cart')->cleanCart();

				return $this->redirect($this->generateUrl('app_catalog_order_thanks'));
			}
		}

		$cart = $this->get('catalog.cart')->loadCart(true);

		return $this->render('AppCatalogBundle:Cart:order.html.twig', array(
			'order' => $cart,
			'form'  => $form->createView()
		));
	}

	public function orderThanksAction()
	{
		return $this->render('AppCatalogBundle:Cart:thanks.order.html.twig');
	}
}