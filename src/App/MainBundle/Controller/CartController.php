<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\Order;
use App\MainBundle\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends Controller
{
    const EMAIL_ORDER_TITLE = 'Новый заказ';

    /**
     * Метод для добавления товаров в корзину.
     *
     * @param Request $request - для получения id товара и количества
     *
     * @return Response - JSON-ответ, полученный из метода getJsonResponse. Нужен для отрисовки дропдауна и страницы заказа.
     *
     * @throws \Exception - если товар не найден в базе.
     */
    public function addAction(Request $request)
    {
        $id = $request->request->getInt('id');
        $quantity = $request->request->getInt('quantity');

        $cartService = $this->get('catalog.cart');
        $cart = $cartService->loadCart();

        $prodRp = $this->getDoctrine()->getRepository('AppMainBundle:Product');
        $product = $prodRp->find($id);

        if (!empty($product)) {
            $cart->addProduct($id, $quantity);
            $cartService->saveCart($cart);
        } else {
            throw new \Exception('Not found product with provided identity');
        }

        return new JsonResponse($cartService->getProductsInfo());
    }

    /**
     * Метод для удаления товаров из корзины.
     *
     * @param Request $request - для получения id товара и количества
     *
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
     *
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
     * Форма заказа.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function orderAction(Request $request)
    {
        $form = $this->createForm(new OrderType(), null, array('csrf_protection' => false));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailRecipients = array(
                $this->container->getParameter('email_info'),
                $form['email']->getData(),
            );

            $mailBody = $this->renderView('AppMainBundle:Cart:email.order.html.twig', array(
                'form' => $form->createView(),
                'cart' => $this->get('catalog.cart')->loadCart(true),
                'person' => $form['person']->getData()
            ));

            $this->get('mailer.manager')->constructAndSendEmail(array(
                'mailTitle' => self::EMAIL_ORDER_TITLE,
                'mailRecipients' => $mailRecipients,
                'mailBody' => $mailBody,
                'mailFiles' => $form['files']->getData())
            );

            $this->get('catalog.cart')->cleanCart();

            return $this->redirect($this->generateUrl('app_catalog_order_thanks'));
        }

        $cart = $this->get('catalog.cart')->loadCart(true);

        return $this->render('AppMainBundle:Cart:order.html.twig', array(
            'order' => $cart,
            'form' => $form->createView(),
        ));
    }

    public function orderThanksAction()
    {
        return $this->render('AppMainBundle:Cart:thanks.order.html.twig');
    }
}
