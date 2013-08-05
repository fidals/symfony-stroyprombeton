<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Gbi;
use App\CatalogBundle\Extension\Transliteration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Рендерим главную страницу
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppCatalogBundle:Default:index.html.twig');
    }
}