<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StaticPageController extends Controller
{

    // ugly urls
    public $baseCats = array(
        537 => 'prom-stroy',
        538 => 'dor-stroy',
        539 => 'ingener-stroy',
        540 => 'energy-stroy',
        541 => 'blag-territory',
        542 => 'neftegaz-stroy'
    );

    // search by alias here and show some result
    public function showAction($alias)
    {
        // prepare alias
        $alias = trim($alias, ' /');

        // search in repository
        $spRepository = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $staticPage = $spRepository->findOneByAlias($alias);
        if(!empty($staticPage)) {
            if($alias == 'obekty'){
                $goRp = $this->getDoctrine()->getRepository("AppMainBundle:GbiObject");
                $objects = $goRp->findAll();
                return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', array(
                    'staticPage' => $staticPage,
                    'objects' => $objects
                ));
            }
            return $this->render('AppMainBundle:StaticPage:staticPage.html.twig', array(
                'staticPage' => $staticPage
            ));
        } else {
            return $this->render('AppMainBundle:StaticPage:404.html.twig');
        }
    }

    public function showIndexAction()
    {
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

        $randomProducts = $this->getDoctrine()
            ->getRepository('AppCatalogBundle:Product')
            ->getRandomProducts();

        foreach($randomProducts as &$product) {
            $path = $catRp->getPath($product->getCategory());
            $product->catUrl = $this->baseCats[$path[0]->getId()];
        }

        return $this->render('AppMainBundle:StaticPage:indexPage.html.twig', array(
            'randomProducts' => $randomProducts
        ));
    }

    public function gbiObjectShow($alias){
        $goRp = $this->getDoctrine()->getRepository("AppMainBundle:GbiObject");
        $go = $goRp->findOneByAlias($alias);
        if($go){
            return $this->render("AppMainBundle:StaticPage:gbiObject.html.twig",array(
                'gbiObject' => $go
            ));
        }
        return $this->render('AppMainBundle:StaticPage:404.html.twig');
    }


}