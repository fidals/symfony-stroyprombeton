<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ObjectController extends Controller
{
	public function showAction($id)
	{
		// search in repository
		$objRepository = $this->getDoctrine()->getRepository('AppMainBundle:Object');
		$object = $objRepository->find($id);

		$twigArgs = array('object' => $object);

		return $this->render('AppMainBundle:Object:object.html.twig', $twigArgs);
	}
}