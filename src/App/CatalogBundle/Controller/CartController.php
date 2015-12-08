<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Order;
use App\CatalogBundle\Form\OrderType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends Controller
{
    /**
     * Метод для добавления товаров в корзину
     *
     * @param Request $request - для получения id товара и количества
     * @return Response - JSON-ответ, полученный из метода getJsonResponse. Нужен для отрисовки дропдауна и страницы заказа.
     * @throws \Exception - если товар не найден в базе.
     */
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

        return new JsonResponse($cartService->getProductsInfo());
	}

    /**
     * Метод для удаления товаров из корзины
     *
     * @param Request $request - для получения id товара и количества
     * @return Response - JSON-ответ, полученный из метода getJsonResponse. Нужен для отрисовки дропдауна и страницы заказа.
     */
	public function removeAction(Request $request)
	{

		$id = $request->request->getInt('id');
		$quantity = $request->request->getInt('quantity');

		$cartService = $this->get('catalog.cart');
		$cart = $cartService->loadCart();

		$cart->removeProduct($id, $quantity);
		$cartService->saveCart($cart);

        return new JsonResponse($cartService->getProductsInfo());
	}

    /**
     * Метод для полной очистки корзины.
     *
     * @return Response
     */
	public function cleanAction()
	{
        $cartService = $this->get('catalog.cart');
        $cartService->cleanCart();

        return new JsonResponse($cartService->getProductsInfo());
	}

    /**
     * Метод для получения информации о текущих товарах в корзине.
     * Нужен для отрисовки дропдауна при загрузке страницы.
     *
     * @return Response
     */
    public function fetchAction()
    {
        $cartService = $this->get('catalog.cart');

        return new JsonResponse($cartService->getProductsInfo());
    }

    /**
     * Метод для апдейта количества товаров в заказе.
     * Получаем новое количество из Реквеста и заменяем им старое, после чего возвращаем JSON-ответ с новой корзиной.
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request)
    {
        $id = $request->request->getInt('id');
        $newCount = $request->request->getInt('count');

        $cartService = $this->get('catalog.cart');
        $cart = $cartService->loadCart();
        $cart->setCount($id, $newCount);
        $cartService->saveCart($cart);

        return new JsonResponse($cartService->getProductsInfo());
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
					->setBody($body);

				$files = $form['files']->getData();

				if (!empty($files) && $files[0] !== null) {  // $files[0] !== null - если не прикреплено файлов, то files[0] будет null
					$fs = new Filesystem();
					$filePath = 'tmp/';

					foreach ($files as $file) {
						$fileName = $file->getClientOriginalName();
						$file->move($filePath, $fileName);
						$fileFullPath = $filePath . $fileName;
						$message->attach(\Swift_Attachment::fromPath($fileFullPath));
					}

					$mailer->send($message);
					$transport = $this->container->get('mailer')->getTransport();
					$spool = $transport->getSpool();
					$spool->flushQueue($this->container->get('swiftmailer.transport.real'));

					foreach ($files as $file) {
						$fs->remove($filePath . $file->getClientOriginalName());
					}
				} else {
					$mailer->send($message);
				}

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