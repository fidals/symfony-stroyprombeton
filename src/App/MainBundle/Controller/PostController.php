<?php

namespace App\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
	const NEWS_PER_PAGE_DEFAULT = 10;

	public function indexAction()
	{
		$postRp = $this->getDoctrine()->getRepository('AppMainBundle:Post');
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$postRp->findAllQuery(),
			$this->getRequest()->query->getInt('page', 1),
			self::NEWS_PER_PAGE_DEFAULT
		);
		return $this->render('AppMainBundle:Post:index.html.twig', array('pagination' => $pagination));
	}

	public function postAction($id)
	{
		$postRp = $this->getDoctrine()->getRepository('AppMainBundle:Post');
		$post = $postRp->find($id);
		return $this->render('AppMainBundle:Post:post.html.twig', array('post' => $post));
	}
}
