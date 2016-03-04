<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Form\OrderDrawingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DrawingController extends Controller
{
    const DRAWING_ARTICLE_ID = 8;
    const EMAIL_ORDER_TITLE = 'Новый заказ на изготовление по чертежам';

    /**
     * Форма заказа изготовления по индивидуальным чертежам
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function orderAction(Request $request)
    {
        $form = $this->createForm(new OrderDrawingType(), null, array('csrf_protection' => false));
        $articleContent = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage')->find(self::DRAWING_ARTICLE_ID);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailRecipients = array(
                $this->container->getParameter('email_info'),
                $form['email']->getData(),
            );

            $mailBody = $this->renderView('AppMainBundle:Drawing:email.order.drawing.html.twig', array(
                'form' => $form->createView(),
                'person' => $form['person']->getData(),
            ));

            $this->get('mailer.manager')->constructAndSendEmail(array(
                'mailTitle' => self::EMAIL_ORDER_TITLE,
                'mailRecipients' => $mailRecipients,
                'mailBody' => $mailBody,
                'mailFiles' => $form['files']->getData())
            );

            return $this->redirect($this->generateUrl('app_order_drawing_thanks'));
        }

        return $this->render('AppMainBundle:Drawing:order.drawing.html.twig', array(
            'article_content' => $articleContent,
            'form' => $form->createView(),
        ));
    }

    public function thanksAction()
    {
        return $this->render('AppMainBundle:Drawing:thanks.order.drawing.html.twig');
    }
}
