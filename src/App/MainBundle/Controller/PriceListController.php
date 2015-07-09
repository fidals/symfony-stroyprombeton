<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Form\PriceListBookingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PriceListController extends Controller
{
	public function bookingAction(Request $request)
	{
		$form = $this->createForm(new PriceListBookingType(), null, array('csrf_protection' => false));

		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);
			if ($form->isValid()) {
				$mailer = $this->get('mailer');
				$message = \Swift_Message::newInstance()
					->setSubject('Stroyprombeton | New price order')
					->setTo(array('info@stroyprombeton.ru', 'as@stkmail.ru', $form['email']->getData()))
					->setFrom('order@stroyprombeton.ru')
					->setContentType("text/html")
					->setBody($this->renderView('AppMainBundle:PriceList:email.booking.html.twig', array(
							'form' => $form->createView()
						))
					);
				$mailer->send($message);
				return $this->redirect($this->generateUrl('app_main_price_list_booking_success'));
			}
		}

		return $this->render('AppMainBundle:PriceList:booking.html.twig', array(
			'form'  => $form->createView()
		));
	}

	public function bookingSuccessAction()
	{
		return $this->render('AppMainBundle:PriceList:success.booking.html.twig');
	}
}