<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StaticPageController extends Controller
{
    /**
     * Смотрим на алиас и отдаем либо страницу либо 404
     * @param $alias
     * @return Response
     */
    public function showAction($alias)
    {
        $alias = trim($alias, ' /');
        $spRepository = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $staticPage = $spRepository->findOneByAlias($alias);
        if(!empty($staticPage)) {
            return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', array(
                'staticPage' => $staticPage
            ));
        } else {
            return $this->render('AppMainBundle:StaticPage:404.html.twig');
        }
    }
}