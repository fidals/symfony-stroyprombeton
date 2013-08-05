<?php

namespace App\ServiceBundle\Controller;

use App\ServiceBundle\Entity\Feedback;
use App\ServiceBundle\Extension\Geolocation;
use App\ServiceBundle\Extension\GeolocationService;
use App\ServiceBundle\Form\FeedbackType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeedbackController extends Controller
{
    public function indexAction()
    {
        $feedback = new Feedback();

        $form = $this->createForm(new FeedbackType(), $feedback);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $feedbackModel = new Feedback();
                $feedbackModel->setName($form->get('name')->getData());
                $feedbackModel->setPhone($form->get('phone')->getData());
                $feedbackModel->setEmail($form->get('email')->getData());
                $em->persist($feedbackModel);
                $em->flush();
                return $this->redirect($this->generateUrl('app_main_index'));
            }
        }

        return $this->render('AppServiceBundle:Feedback:feedback.html.twig', array(
            'form' => $form->createView()
        ));
    }
}