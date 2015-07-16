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
				$recipients = array(
					$this->container->getParameter('email_info'),
					$this->container->getParameter('email_as'),
					$form['email']->getData()
				);
				$body = $this->renderView('AppMainBundle:PriceList:email.booking.html.twig', array(
					'form' => $form->createView()
				));
				$message = \Swift_Message::newInstance()
					->setSubject('Stroyprombeton | New price order')
					->setTo($recipients)
					->setFrom($this->container->getParameter('email_order'))
					->setContentType("text/html")
					->setBody($body);
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