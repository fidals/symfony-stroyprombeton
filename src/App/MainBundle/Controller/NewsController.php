<?php

namespace App\MainBundle\Controller;

use App\CatalogBundle\Entity\Trademark;
use App\CatalogBundle\Entity\Repository;
use App\CatalogBundle\Extension\Transliteration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{
    public function showAllAction()
    {
        $newsRP=$this->getDoctrine()->getRepository('AppMainBundle:News');
        $allnews=$newsRP->findAll();
        return $this->render('AppMainBundle:Default:allnews.html.twig', array(
           'allnews'=>$allnews
        ));
    }
    public function showFullAction($newsId)
    {
        $newsRP=$this->getDoctrine()->getRepository('AppMainBundle:News');
        $news=$newsRP->findOneById($newsId);
        return $this->render('AppMainBundle:Default:news.html.twig', array(
            'news'=>$news
        ));
    }

}
