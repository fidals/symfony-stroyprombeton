<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TerritoryController extends Controller
{
	public function mapAction()
	{
		$territoryRp = $this->getDoctrine()->getRepository('AppMainBundle:Territory');
		$territories = $territoryRp->findAll();
		$twigArgs    = array('territories' => $territories);

		return $this->render('AppMainBundle:Territory:map.html.twig', $twigArgs);
	}

	public function showAction($territoryId)
	{
		// search in repository
		$territoryRp = $this->getDoctrine()->getRepository('AppMainBundle:Territory');
		$territory   = $territoryRp->find($territoryId);

		if (!empty($territory)) {
			$twigArgs = array('territory' => $territory);

			return $this->render('AppMainBundle:Territory:show.html.twig', $twigArgs);
		} else {
			return $this->render('AppMainBundle:StaticPage:404.html.twig');
		}
	}
}